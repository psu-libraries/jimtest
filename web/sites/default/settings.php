<?php

// phpcs:ignoreFile

/**
 * @file
 * Drupal site-specific configuration file.
 *
 * This file contains common settings.php and controls when ddev and k8s
 * settings files should be loaded. Comments have been removed for readability.
 * Documentation and additional settings can be found in default.settings.php.
 *
 * @see default.settings.php
 */

/**
 * Database settings:
 */
$databases = [];

/**
 * Salt for one-time login links, cancel links, form tokens, etc.
 *
 * The actual local value is in settings.ddev.php and the production salt
 * is pulled from environment variables (see settings.k8s.php).
 */
$settings['hash_salt'] = 'NvKkSatwkoQ-_L-l9QrfwTqm3xQJxhxMLV7wUz6jSG4uSLbJw-e069pe902H7pOl6eRiFKcJvw';

/**
 * Access control for update.php script.
 */
$settings['update_free_access'] = FALSE;

/**
 * Private file path:
 */
$settings['file_private_path'] = $app_root . '/../private';

/**
 * Load services definition file.
 */
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';

/**
 * The default list of directories that will be ignored by Drupal's file API.
 */
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];

/**
 * The default number of entities to update in a batch process.
 */
$settings['entity_update_batch_size'] = 50;

/**
 * Entity update backup.
 */
$settings['entity_update_backup'] = TRUE;

/**
 * Node migration type.
 */
$settings['migrate_node_migrate_type_classic'] = FALSE;

/**
 * Load local development override configuration, if available.
 */
#
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

/**
 * Location of the site configuration files.
 */
$settings['config_sync_directory'] = '../config/sync';

/**
 * Do not export configurations for these development modules.
 */
$settings['config_exclude_modules'] = [
  'migrate_devel',
  'unused_modules',
];

/**
 * Load .env file from web/sites/default/.env
 */
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

/**
 * Matomo settings.
 *
 * This should only be set in k8s environment, and should be pulled from
 * environment variables.
 */
$config['matomo.settings']['site_id'] = $_ENV['MATOMO_SITE_ID'] ?? '';
if (isset($_ENV['MATOMO_HOST']) && $_ENV['MATOMO_HOST'] !== FALSE) {
  $config['matomo.settings']['url_http'] = 'http://' . $_ENV['MATOMO_HOST'] . '/matomo/';
  $config['matomo.settings']['url_https'] = 'https://' . $_ENV['MATOMO_HOST'] . '/matomo/';
}

/**
 * Memcache settings.
 *
 * If this site requires memcache then load the memcache settings file.
 */
// $memcache_settings = dirname(__FILE__) . '/settings.memcache.php';
// if (file_exists($memcache_settings)) {
//   require $memcache_settings;
// }

// Automatically generated include for settings managed by ddev.
$ddev_settings = dirname(__FILE__) . '/settings.ddev.php';
if (getenv('IS_DDEV_PROJECT') == 'true' && is_readable($ddev_settings)) {
  require $ddev_settings;
  $config['config_split.config_split.dev']['status'] = TRUE;

  // Add the ddev memcache server if required for the site.
  // $settings['memcache']['servers']['memcached:11211'] = 'default';
} elseif (file_exists($app_root . '/' . $site_path . '/settings.k8s.php')) {
  include $app_root . '/' . $site_path . '/settings.k8s.php';
}
