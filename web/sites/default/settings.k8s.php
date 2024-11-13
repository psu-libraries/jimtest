<?php

/**
 * @file
 * #ddev-generated: Automatically generated Drupal settings file.
 * ddev manages this file and may delete or overwrite the file unless this
 * comment is removed.  It is recommended that you leave this file alone.
 */

$host = getenv('MYSQL_HOST');
$port = 3306;
$driver = "mysql";

$settings['reverse_proxy'] = TRUE;
$settings['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];

$databases['default']['default']['database'] = getenv('MYSQL_DATABASE');
$databases['default']['default']['username'] = getenv('MYSQL_USER');
$databases['default']['default']['password'] = getenv('MYSQL_PASSWORD');
$databases['default']['default']['host'] = $host;
$databases['default']['default']['driver'] = $driver;
$databases['default']['default']['port'] = $port;
$databases['default']['default']['init_commands']['isolation_level'] = 'SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED';

$settings['hash_salt'] = getenv('DRUPAL_HASH_SALT');

// Prevent an deployed environemnt from writing configuration.
if (strtolower(getenv('DRUPAL_CONFIG_READONLY')) === "false") {
  $settings['config_readonly'] = FALSE;
}
else {
  $settings['config_readonly'] = TRUE;
  $settings['config_readonly_whitelist_patterns'] = [
    'search_api.index.default_index',
    'auditfiles.settings',
    // Allowing menu form to be edited.
    // @see https://github.com/psu-libraries/hershey/issues/172
    'system.menu.*',
    // Optionally allow webforms to be edited.  Config Ignore configs need to
    // be updated.
    // 'webform.webform.*',
    // 'webform.webform_options.*',
  ];
}

// Turn on prod split for deployed environemnts.
$config['config_split.config_split.prod']['status'] = TRUE;

// This will prevent Drupal from setting read-only permissions on sites/default.
$settings['skip_permissions_hardening'] = TRUE;

// This will ensure the site can only be accessed through the intended host
// names. Additional host patterns can be added for custom configurations.
$settings['trusted_host_patterns'] = ['.*'];

/**
 * Memcache settings.
 *
 * If this site requires memcache then load the memcache settings file.
 */
// if (class_exists('Memcached', FALSE)) {
//   // Use memcache for bootstrap, discovery, config instead of fast chained
//   // backend to properly invalidate caches on multiple webs pods.
//   // See https://www.drupal.org/node/2754947
//   $settings['cache']['bins']['bootstrap'] = 'cache.backend.memcache';
//   $settings['cache']['bins']['discovery'] = 'cache.backend.memcache';
//   $settings['cache']['bins']['config'] = 'cache.backend.memcache';
//   $settings['cache']['default'] = 'cache.backend.memcache';
//
//   // We presently support up to 3 MEMCACHED_HOST.
//   if (getenv('MEMCACHED_HOST_0')) {
//     $memcached_host = getenv('MEMCACHED_HOST_0') . ':' . getenv('MEMCACHED_PORT');
//     $settings['memcache']['servers'][$memcached_host] = 'default';
//   }
//
//   if (getenv('MEMCACHED_HOST_1')) {
//     $memcached_host = getenv('MEMCACHED_HOST_1') . ':' . getenv('MEMCACHED_PORT');
//     $settings['memcache']['servers'][$memcached_host] = 'default';
//   }
//
//   if (getenv('MEMCACHED_HOST_2')) {
//     $memcached_host = getenv('MEMCACHED_HOST_2') . ':' . getenv('MEMCACHED_PORT');
//     $settings['memcache']['servers'][$memcached_host] = 'default';
//   }
// }

// Don't use Symfony's APCLoader. ddev includes APCu; Composer's APCu loader has
// better performance.
$settings['class_loader_auto_detect'] = FALSE;

if (getenv('DRUPAL_TMP')) {
  $settings['file_temp_path'] = getenv('DRUPAL_TMP');
}

// Adding D7 Migration configs.  If no migrations is planned then this can be
// removed.
if (getenv('DRUPAL_MIGRATE_MYSQL_DATABASE')) {

  $settings['migrate_source_version'] = '7';
  $settings['migrate_source_connection'] = 'migrate';
  $settings['migrate_file_public_path'] = getenv('DRUPAL_MIGRATE_FILE_PUBLIC_PATH');
  $settings['migrate_file_private_path'] = getenv('DRUPAL_MIGRATE_FILE_PRIVATE_PATH');

  $port = getenv('DRUPAL_MIGRATE_MYSQL_PORT') ?? 3306;

  $databases['migrate']['default']['database'] = getenv('DRUPAL_MIGRATE_MYSQL_DATABASE');
  $databases['migrate']['default']['username'] = getenv('DRUPAL_MIGRATE_MYSQL_USERNAME');
  $databases['migrate']['default']['password'] = getenv('DRUPAL_MIGRATE_MYSQL_PASSWORD');
  $databases['migrate']['default']['host'] = getenv('DRUPAL_MIGRATE_MYSQL_HOST');
  $databases['migrate']['default']['port'] = $port;
  $databases['migrate']['default']['driver'] = 'mysql';

}
