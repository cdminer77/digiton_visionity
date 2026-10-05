<?php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', true);
define('DB_NAME', 'visionity_staging');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', '');
$table_prefix = 'qhaupsn_';

require_once __DIR__ . '/wp-includes/class-wpdb.php';
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
echo "wpdb ready: " . ($wpdb->ready ? "YES" : "NO") . "\n";
echo "wpdb has_connected: " . ($wpdb->has_connected ? "YES" : "NO") . "\n";
echo "wpdb error: " . $wpdb->last_error . "\n";
if ($wpdb->error) {
    echo "wpdb->error details:\n";
    var_dump($wpdb->error);
}

$wpdb->set_prefix($table_prefix);
$row = $wpdb->get_row("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'siteurl'");
echo "siteurl option: " . ($row ? $row->option_value : "NULL (Query error: " . $wpdb->last_error . ")") . "\n";
