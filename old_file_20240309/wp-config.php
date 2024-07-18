<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', '9cuwh_47x76n35' );

/** Database username */
define( 'DB_USER', '9cuwh_t4n54cet' );

/** Database password */
define( 'DB_PASSWORD', '5Q73876j$jB' );

/** Database hostname */
define( 'DB_HOST', 'mysql12.onamae.ne.jp' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',          'gKERW}>U,dA#f^A@bIZ(r]<}j0+%`=Y;[6z)W|H5bI!yJ?dWhe4.X8G0_)5ABS{^' );
define( 'SECURE_AUTH_KEY',   'Ek-Z:9{q@n=GYH#Ue0MM@VGK]UXHuS~)ckg.hP!Q6PRmG9<crBWP-$mrq>A)=4mV' );
define( 'LOGGED_IN_KEY',     'UMWa]Ysk&<z6{K&c;oa3Wz:6C,/e7[|(H-o,3dc#SYq?z3gGQuBT}t0sr<b]$z}@' );
define( 'NONCE_KEY',         ';>oD7VhEcylCwPhKv|Bb/ r|t#cUiX6jFmJ26Bki=Yim`}$=M2ENgpU*`+r~M=oM' );
define( 'AUTH_SALT',         '94?Tui3&Ti}pr,JUjeg:* 47C%RWJ,*~u>[0*bEsLe:$}BX.n5{>+<86@(HnaC~m' );
define( 'SECURE_AUTH_SALT',  'Z`9pE=smD%8u?7RLwGI-SWQUoOZb>aymMO$zx#B2`G$VQHq{mUUd0Agm+<!dya_,' );
define( 'LOGGED_IN_SALT',    'Pwnm`k(3<Ztl`L)%4AfF~vchQ?OzB_0t382Wv-r-r{d.QCBQH7nMkf1V=04P5(cL' );
define( 'NONCE_SALT',        'F9>5Z*Te1(%HUeiOJ9[5:TXsec#SnA!2dVvI7&*R*qa?Ouw{3N<oG7XH[WyXS<l!' );
define( 'WP_CACHE_KEY_SALT', 'q~]}C?bp1Ll^hc LhobAEh[$C2N%bSxZ=*u*a<c/0pO^ReV:N+Jl=%;8bE6CZdQw' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === "https") {
    $_SERVER['HTTPS'] = 'on';
    define('FORCE_SSL_LOGIN', true);
    define('FORCE_SSL_ADMIN', true);
}


/* Add any custom values between this line and the "stop editing" line. */



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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'RS_DASHBOARD_PLUGIN_SID', 'CUPQRSZpZVHhen3v0AtaTMX2SOBTsFo-fruftOmKmjnF76ZiAs5js3wAK8ZLpcmJBzxizbFwrVG4PL-oQF9tG3ohLgqzy2ODj71pwO8bbTk.' );
define( 'RS_DASHBOARD_PLUGIN_DID', 'W-HJWfaT1x_g8aDRUGYocsnCIyZA6LR4odDG4r0xPfn0EQfPNe9cC6OedyeniRleLGmqNuueyl8Ntkre8steqX4oXraeUlBfMmtjLW1eoxM.' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
