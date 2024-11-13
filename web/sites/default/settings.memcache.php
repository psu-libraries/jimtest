<?php

/**
 * @file
 */

use Composer\Autoload\ClassLoader;

// Memcache configurations.
$memcached_exists = class_exists('Memcached', FALSE);
$memcache_services_yml = DRUPAL_ROOT . '/modules/contrib/memcache/memcache.services.yml';

if ($memcached_exists) {
  $settings['memcache']['extension'] = 'Memcached';

  if (class_exists(ClassLoader::class)) {
    $class_loader = new ClassLoader();
    $class_loader->addPsr4('Drupal\\memcache\\', DRUPAL_ROOT . '/modules/contrib/memcache/src');
    $class_loader->register();
    $settings['container_yamls'][] = $memcache_services_yml;

    // Enable compression for PHP 7.
    $settings['memcache']['options'][Memcached::OPT_COMPRESSION] = TRUE;

    // Decrease latency.
    $settings['memcache']['options'][Memcached::OPT_TCP_NODELAY] = TRUE;

    // Define custom bootstrap container definition to use Memcache for
    // cache.container.
    $settings['bootstrap_container_definition'] = [
      'parameters' => [],
      'services' => [
        'database' => [
          'class' => 'Drupal\Core\Database\Connection',
          'factory' => 'Drupal\Core\Database\Database::getConnection',
          'arguments' => ['default'],
        ],
        'settings' => [
          'class' => 'Drupal\Core\Site\Settings',
          'factory' => 'Drupal\Core\Site\Settings::getInstance',
        ],
        'memcache.settings' => [
          'class' => 'Drupal\memcache\MemcacheSettings',
          'arguments' => ['@settings'],
        ],
        'memcache.factory' => [
          'class' => 'Drupal\memcache\Driver\MemcacheDriverFactory',
          'arguments' => ['@memcache.settings'],
        ],
        'memcache.timestamp.invalidator.bin' => [
          'class' => 'Drupal\memcache\Invalidator\MemcacheTimestampInvalidator',
          // Adjust tolerance factor as appropriate when not running memcache on
          // localhost.
          'arguments' => [
            '@memcache.factory',
            'memcache_bin_timestamps',
            0.001,
          ],
        ],
        'memcache.backend.cache.container' => [
          'class' => 'Drupal\memcache\DrupalMemcacheInterface',
          'factory' => ['@memcache.factory', 'get'],
          'arguments' => ['container'],
        ],
        'cache_tags_provider.container' => [
          'class' => 'Drupal\Core\Cache\DatabaseCacheTagsChecksum',
          'arguments' => ['@database'],
        ],
        'cache.container' => [
          'class' => 'Drupal\memcache\MemcacheBackend',
          'arguments' => ['container', '@memcache.backend.cache.container', '@cache_tags_provider.container', '@memcache.timestamp.invalidator.bin'],
        ],
      ],
    ];

    // Use memcache as the default bin.
    $settings['cache']['default'] = 'cache.backend.memcache';
  }

}
