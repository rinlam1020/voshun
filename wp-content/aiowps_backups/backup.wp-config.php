<?php
define('WP_CACHE', false); // Added by WP Rocket
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
define('DB_NAME', 'wp_toannang_wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '1234qwer');

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
define('AUTH_KEY',         'XXC#8v8@DzmM!QhNw^{O|{]inM~({FL Vp`a=TcVq(6Aw2_sZ?xf{j_{T!i7#&N1');
define('SECURE_AUTH_KEY',  'SJl|EL4c4C7%9a^luB^AI-t,[6X-V]3/:QA!fEn;{rI$hW7}%JXv#,eN SMa0{&B');
define('LOGGED_IN_KEY',    'RwM*5Voy]AR(b40LHVQTpp6,CU[=di=oJr[><,V6l/iQz4RYbYC?0`E*y8hexo;*');
define('NONCE_KEY',        'g&{w-0F=4H+R,#_3d% u #Wj08hYkd^Xciu&>=u htq]kC*b%U>aRBH97o9O7&m@');
define('AUTH_SALT',        'b>y_NkCJ2Zxh^Y=u/-0>!}z1P]FcNobZYg8)X8-`U3c-,Fp~O^O.i=6iCS`CQHVk');
define('SECURE_AUTH_SALT', '8d-Bj&eND$BKM Wf`QIME!A](.>F_clj^&P;H>f>xEgU~&([RJ0s*QW~zA:zM7Ck');
define('LOGGED_IN_SALT',   'rrKb[cgbky6cL#2IYt@~6NsKPh_+4c;f&RGN=A}Sjz)_(@u{fM8yoA^yj^<PK>)f');
define('NONCE_SALT',       'ro:21$qMHx/Gf_EqT7X1.}}!C!E%j+<yNoj,vYh_l}8 ?~| #sKbAr>(jrUgvaX&');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
//define("ADMIN_COOKIE_PATH", "/administrator");

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
