<?php

/** Database settings */
if ($_SERVER["HTTP_HOST"] === 'dev.madebyvital.com') {
    define('WP_ENV', 'development');
} else {
    define('WP_ENV', 'production');
}

if (WP_ENV == 'development') {
    define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp');
    define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME']);
    define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
    define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');

    define('DB_NAME', 'dev_db_name');
    define('DB_USER', 'dev_db_user');
    define('DB_PASSWORD', 'dev_db_password');
    define('DB_HOST', 'localhost');
} else {
    define('WP_SITEURL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp');
    define('WP_HOME', 'http://' . $_SERVER['SERVER_NAME']);
    define('WP_CONTENT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/wp-content');
    define('WP_CONTENT_URL', 'http://' . $_SERVER['SERVER_NAME'] . '/wp-content');

    define('DB_NAME', 'prod_db_name');
    define('DB_USER', 'prod_db_user');
    define('DB_PASSWORD', 'prod_db_password');
    define('DB_HOST', 'localhost');
}

/** Database charset and collate type. */
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

/** Authentication Unique Keys and Salts. */
define('AUTH_KEY',         'put your unique phrase here');
define('SECURE_AUTH_KEY',  'put your unique phrase here');
define('LOGGED_IN_KEY',    'put your unique phrase here');
define('NONCE_KEY',        'put your unique phrase here');
define('AUTH_SALT',        'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT',   'put your unique phrase here');
define('NONCE_SALT',       'put your unique phrase here');

/** WordPress Database Table prefix. */
$table_prefix  = 'wp_';

/** WordPress Localized Language, defaults to English. */
define('WPLANG', '');

/** Disable uploading plugins/themes */
define( 'DISALLOW_FILE_MODS', true );

/** Disable auto updates **/
define( 'AUTOMATIC_UPDATER_DISABLED', true );

/** Enable WP_DEBUG mode */
/** NEVER LEAVE THESE OPTIONS ON IN PRODUCTION!!! */
define( 'WP_DEBUG', false );
/** Save Database Queries for later analysis */
// define( 'SAVEQUERIES', true );
/** Enable Debug logging to the /wp-content/debug.log file */
// define( 'WP_DEBUG_LOG', true );
/** Disable display of errors and warnings */
// define( 'WP_DEBUG_DISPLAY', false );
// @ini_set('display_errors', 0 );
/** Use non-minified core styles and scripts */
// define( 'SCRIPT_DEBUG', true );

/** Absolute path to the WordPress directory. */
if ( !defined( 'ABSPATH' ) )
    define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');