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
define ('WP_MEMORY_LIMIT', '512M');
define( 'WP_HOME', 'http://smart-sonia.eu.144-76-38-75.comitech.gr/' );
define( 'WP_SITEURL', 'http://smart-sonia.eu.144-76-38-75.comitech.gr/' );
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'my_db');

/** MySQL database username */
define('DB_USER', 'my_user');

/** MySQL database password */
define('DB_PASSWORD', 'my_password');

/** MySQL hostname */
define('DB_HOST', 'localhost:3306');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'QL9nZii3L^e!66ib!LL6!i(MomTlpmfaK#^IEHg6zjPG3P!rE2BKcIQiSC)5Gw(k');
define('SECURE_AUTH_KEY',  'cDHVy6RIfwRog0N)NpBpbNeOJVLRiwELlYvkcBElTFQ)5ve#A3w#(!HosdtVVw7t');
define('LOGGED_IN_KEY',    'TUoqB%ZdDBS5Cgr3qxlZKu&kjjgbyxxdl^pr95lxgTi^PA3TqSk2!ii6aSO6zIM6');
define('NONCE_KEY',        'cRdlyJDKw2YZZZy5MRcGm)57c9HMP!Qh4dqoGCViZQ)&gnGOVtmKsDMIJsF&0GHJ');
define('AUTH_SALT',        'kvZVNXSFTmewyPpZ5lNxD#1gB9!zfnyGB343#&QvLYjvkEI2walDU&CAzIYHVU)f');
define('SECURE_AUTH_SALT', '@Ig3McTkPI#h0ULE08vs@FYDHVsBUdpLLTwu2JTH5#102Q6uKMZw)%woLeuTh2BN');
define('LOGGED_IN_SALT',   'HO66r8QXVUjPi*oh5Q7HoUnv2ylfCIm(s#0a4(pkjWowmysvSymSLPBrJEKYxOg1');
define('NONCE_SALT',       'auBgkisFCx(X&Ov0jhPHGO7NMQyERM!YUSZ^mL@u#w2M*7KQt50jblGcpy4V5xsi');
/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '5QSOZ7YX9_';

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

define( 'DISALLOW_FILE_EDIT', true );
define( 'CONCATENATE_SCRIPTS', false );
define( 'WP_AUTO_UPDATE_CORE', false );
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define( 'WP_ALLOW_MULTISITE', true );

define ('FS_METHOD', 'direct');
