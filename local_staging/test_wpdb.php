<?php
define('WP_USE_THEMES', false);
require_once __DIR__ . '/wp-load.php';
global $wpdb;

echo "wpdb check:\n";
echo "DB User: " . DB_USER . "\n";
echo "DB Host: " . DB_HOST . "\n";
echo "DB Name: " . DB_NAME . "\n";
echo "Table prefix: " . $wpdb->prefix . "\n";
echo "Has error: " . ($wpdb->last_error ? $wpdb->last_error : "None") . "\n";
$posts = $wpdb->get_results("SELECT ID, post_title FROM {$wpdb->prefix}posts WHERE post_type='page' LIMIT 5");
echo "Found pages: " . count($posts) . "\n";
foreach ($posts as $p) {
    echo "  - [{$p->ID}] {$p->post_title}\n";
}
