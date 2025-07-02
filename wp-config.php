<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '7vals-task' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

if ( !defined('WP_CLI') ) {
    define( 'WP_SITEURL', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
    define( 'WP_HOME',    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] );
}



/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'zbTQWUE8cT12OnWjCXz4gyphUzOhb9q0mYeJtj01FV4zc7OPJi94IGu0rIYewM84' );
define( 'SECURE_AUTH_KEY',  'loF35GKzKFRIcEo6XP6VTiyaX5rl7VBMzPecScQ6c7zXdi5IfMdFE1eGr8MUNWAL' );
define( 'LOGGED_IN_KEY',    'BuD3prxsnh2gucRgDLbv4bHv0eMzkgp6KrLUz9g8GcdD9vqzQNhc70gcha5aoOuM' );
define( 'NONCE_KEY',        'K7zPzfMHQV13s7EyXuCYLs5hRZM5b1edGAG45f6MaejBOp04BhnjOeeDnUv3VvdV' );
define( 'AUTH_SALT',        'Mp0k8uE77Wpqm9umtAQXTe6DUcFedVnrYNN9YB0YDPjhPfn4DnTwRQrFvVrKIjpy' );
define( 'SECURE_AUTH_SALT', '4kspRThcNcYApUdaBWvUcZPPhvN3TtRtzSxl5sK88iuxadC20fBxdn0xApmN1UGd' );
define( 'LOGGED_IN_SALT',   'tqtPBx8kKKuIkL66YkUIe8vjuuPKP3F9dpi082kSCxgEA1eAmnyV9HjaT3mMJ8U1' );
define( 'NONCE_SALT',       '7txFJ41J2fnqRXnK40EJ9uIVHixUuOv9mPh3ye4m9Vabwo8ScBoavdCVGh5wzErA' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */

define( 'WP_DEBUG', true );
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
