<?php

/** Database settings */
if ( file_exists( dirname( __FILE__ ) . '/wp-config-local.php' ) ) {
    define( 'WP_LOCAL_DEV', true );
    include( dirname( __FILE__ ) . '/wp-config-local.php' );
} else {
    define( 'WP_LOCAL_DEV', false );
    define( 'DB_NAME', 'DB_NAME' );
    define( 'DB_USER', 'DB_USER' );
    define( 'DB_PASSWORD', 'DB_PASSWORD' );
    define( 'DB_HOST', 'localhost' );
}

/** Custom content directory */
define( 'WP_CONTENT_DIR', dirname( __FILE__ ) . '/content' );
define( 'WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content' );

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

/** For developers: WordPress debugging mode. */
define('WP_DEBUG', false);

/** Absolute path to the WordPress directory. */
if ( !defined( 'ABSPATH' ) )
    define( 'ABSPATH', dirname( __FILE__ ) . '/wp/' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');