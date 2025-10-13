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
define( 'DB_NAME', 'suspension-advisory' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'admin123' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         '|/qUi4+#W[tB1WGj-cd[P#$O]z?PwC(rQ>|+@)^-a%y?2EMBFD[]6&D0*ME_;FDv');
define('SECURE_AUTH_KEY',  'p6g6c%eoZQ_U+tB(]gDIl%[n!b*ld:p/Wtjs$x9; A. 7zJq#U~aSy?}p+k^-r|<');
define('LOGGED_IN_KEY',    '?ioF,RecjxY;vb4IX8(0`yHr|&>]&r!#tLzp51suQ_#)A}~7zGuJRCXUN781*D>/');
define('NONCE_KEY',        '$k|Lfa5zCnh`r2Gd4;dE9ow:L+7s)@,=w^vi$!A3uKD&J>fu u1`QbnQmpZ-5N_m');
define('AUTH_SALT',        'LwH]R$eiUx5~3hF;UdwN$avRe<19KpUEi?yY%hY.%j1MV4-fEiI1OJN/J|DH/i,n');
define('SECURE_AUTH_SALT', '8A*SrS_Dg9P_d.>p5O#ky_VNk/v,Am0//D@>!?6)_xCC>yyt{Nfl?.B@7?-:H6N^');
define('LOGGED_IN_SALT',   'q+ldi2DWn$+;]*H(4;{*,opKW;68J<j1zsyBN1]HTf&-f;b|+r@hur3gx|m~!Vr?');
define('NONCE_SALT',       'QF(CxF(E9|KHTG_>+C,]y,Aw/[xvBQe+xJu5cN@|CB_GE$Px*#bdU[.BymyphG8g');

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
