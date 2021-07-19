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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'blog' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost:3306' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'XK4Q55 p q P;N1divNK>OaI !Kq:w5w GJqyJD&*=2/.*Z^iiwnnnu%dvU)X/C~' );
define( 'SECURE_AUTH_KEY',  'ZV^iMbK(IDSdr22(r-2m<6t&2I: 6qixw0]7xaLQO|Wi?i.2?vp7WnP09;wnygfE' );
define( 'LOGGED_IN_KEY',    'YT?R,U.dy}fBnu14WT{/k#+8E>1[@f@[S]:tz=kGzHq_`dS%Z5xt/Pp_hWf6u28Q' );
define( 'NONCE_KEY',        'j9{zvK)+D@rx{esV-~Yr0xd?l-dVh O6I&AS.eOY+|yYfdL:~c`Y9.lj^d^1XBxO' );
define( 'AUTH_SALT',        '51q9>htDG>9vG[|,Yn?+*}}=9=M$#VZp{SJ)*,^sl009EiT14qtqFtBk7+Ff5w_h' );
define( 'SECURE_AUTH_SALT', 'Kjz;NE4NZ,h3k.qwODfusWh^S!Jh[R|{#X+3N.P>JQBN}X0G#8(%,Poo5=jXGT/q' );
define( 'LOGGED_IN_SALT',   'ml)vkQ@j[s8p2r<X|.3!);M(PQn8k^-Lm*+4Dj;RlDnd(B6YA9*)-q^M8UqbK$4~' );
define( 'NONCE_SALT',       'xbER:E)Y2pG(W^i;G2O!q,xjS|o=T_L*,M@]&f -}des;_:/l:_gAu#gkM+#OHQ7' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
