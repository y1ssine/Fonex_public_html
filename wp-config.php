<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'fonexinc_wp966');

/** MySQL database username */
define('DB_USER', 'fonexinc_wp966');

/** MySQL database password */
define('DB_PASSWORD', '18Spx[9@25');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'qrkkgyzusq9akdnsehigzw8tibcxbaoy2ezvodcxf2aoauoaue4oznt36zcsziqh');
define('SECURE_AUTH_KEY',  'j9qjfr6hzftgi7kxjckz2g0ukhf1eriw87owr9nufaulofjwtnf1murrlokeevff');
define('LOGGED_IN_KEY',    'kt3iehsfbejivghygqdxtez8sqdlrgghadihgugxij33e5cs5jjqoov13zf5tmsx');
define('NONCE_KEY',        'gqrgwk4zps4zgep994wgpsv40vkpn8utklypfl0efeiolppdh3pfgjhokh4etda2');
define('AUTH_SALT',        'pixfixjhzkkyk0wuwyhloozehsjtg2gg2i3lhmf8vzjkielt6xn2t8kbw8k5bt9g');
define('SECURE_AUTH_SALT', '7sw3alhb32k4l4zynqvc8gmqqf0fcvrxwctjchkkesrzkkridglpiewry9opkhdf');
define('LOGGED_IN_SALT',   'ciyds474ugkcm7bw06siqyirunb3nu836digk7ayyd5d2maztqenjrvxk1sszmuf');
define('NONCE_SALT',       'iwgkojowaga3ufzwgh1sqvmanhzkaglv1jhysrvswik6yd9z4t1zbjgyp3l5bw2n');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpjd_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define( 'WP_MEMORY_LIMIT', '128M' );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');



@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
