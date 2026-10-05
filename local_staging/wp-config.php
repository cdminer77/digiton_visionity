<?php
/**
 * The base configuration for WordPress
 */

// Disable WP_CACHE locally
define('WP_CACHE', false);

// ** Database settings ** //
define('DB_NAME', 'visionity_staging');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 */
define('AUTH_KEY', 'dG|A7T6y&*7*Tf1prDH)mWTw@%ku][];x_IKSs@I645p_&3usRp9Bo49fc(OOMo2');
define('SECURE_AUTH_KEY', '1jGF&YGB5qC976ct:30wV+647emNZp(80h9yR)hOPEB8Nu90lEEFJkqE#5F4nF0&');
define('LOGGED_IN_KEY', 'j*o[pfFm~0BQ*/u+rcKD(K!LLu/5jM38O[E!A5fq8E5nK2870nUnHViO%;%_;We8');
define('NONCE_KEY', 'X0:6KE*%F67@*ZpCvDlmK!9|5~D93EUY9i856;Z2]dP!D!|#|PdN0%8e~Z)9Yv!9');
define('AUTH_SALT', 'QrwHz0NQn~&v~BjRl9xdt/yFp5J/5[O1SH4478Q:W67@L%3nD58iZk270@j02A]P');
define('SECURE_AUTH_SALT', 'K7T81[i]2bT8S*1wMKH8!/+9@8G!Zjw~Ti:40i/z67N0(#m~gXS/X6*j#9;Kb2q9');
define('LOGGED_IN_SALT', 'y~h4(45S1nG8G1x+LA)G3U2&@T8S3R7aDC%@|9Ds]3xd42o]qk*hC37)t2H_vO@x');
define('NONCE_SALT', '#-/Did])o%-nab#9En4Wf9yK5P08Flw#P~7%O]#LI288WNw|sfuy5C!q|9V08[*8');

/**
 * WordPress database table prefix.
 */
$table_prefix = 'qhaupsn_';

// Local URLs
define('WP_HOME', 'http://127.0.0.1:8080');
define('WP_SITEURL', 'http://127.0.0.1:8080');

define( 'FORCE_SSL_ADMIN', false );
define( 'FORCE_SSL_LOGIN', false );
$_SERVER['HTTPS'] = 'off';
define( 'WP_HTTP_BLOCK_EXTERNAL', true );
define( 'WP_ACCESSIBLE_HOSTS', '127.0.0.1, localhost' );
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );
@ini_set( 'display_errors', 0 );

define( 'DUPLICATOR_AUTH_KEY', 'r voMV_n2#esYAer2qZAbN@ElfaX*Kr#]SlT7)%sqiNd_%{eVIv>G6xnP%/z}P2B' );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
