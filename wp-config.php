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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gasolina' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'MLk;hn.js;jt7Q?lRrn?$h@V^T&aBJ81l6<Ea&q_({&|O{qu2~RbfBrj@s!mI$qi' );
define( 'SECURE_AUTH_KEY',  ')1c:e:A{^z_4Nr81!_$aQxU[-Uf$$g*ZLZUq1P3B{67LV&c3g.J_}$oW55E5|iH.' );
define( 'LOGGED_IN_KEY',    'd8c|*5Sxn `cAnbna`yEt8WBgy.K<Ge6dEqW>]@K5}-NFaY:DEAlycFH#>w7Ol3>' );
define( 'NONCE_KEY',        'pS:y+MD|X4Cv)`l|w},Z?j.Xe6$Ec,]zx=r?~r*Iv2=C? RYC-$>4 .jc_M=2L[p' );
define( 'AUTH_SALT',        '.9&~ym,<gc3lj02=P8F@aIUQPb-(6(``C3*A7mn;p unhv%W]}iF,*.Mq-XCs5pr' );
define( 'SECURE_AUTH_SALT', 'xE.~8bh JYY/N3N;=_70xdB{QFOXavLV%!w&v>9WmnPh=OiYe<iM_t;N=g2(=`Bq' );
define( 'LOGGED_IN_SALT',   'Dh!9_7X$=ssD.3xN.l[t;U7K40CB>~-B)PvzcJfdRy@gMhgV|3h$7-=9gWq}Vf}k' );
define( 'NONCE_SALT',       'A~~nBn1h#<ig(1oRy$YRB+AIn1@bJYRmB&|26f0 m:^/+Gx}e;t2,(aP,KJt-7:+' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
