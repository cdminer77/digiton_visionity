<?php
// Safe serialized search replace for WordPress
require_once __DIR__ . '/wp-load.php';
global $wpdb;

$from = 'https://visionity.digiton.it';
$to   = 'http://127.0.0.1:8080';

$from_esc = 'https:\/\/visionity.digiton.it';
$to_esc   = 'http:\/\/127.0.0.1:8080';

function replace_recursive($data, $from, $to) {
    if (is_string($data)) {
        return str_replace($from, $to, $data);
    }
    if (is_array($data)) {
        foreach ($data as $k => $v) {
            $data[$k] = replace_recursive($v, $from, $to);
        }
    }
    if (is_object($data)) {
        foreach ($data as $k => $v) {
            $data->$k = replace_recursive($v, $from, $to);
        }
    }
    return $data;
}

function process_value($val, $from, $to) {
    if (!is_string($val)) return $val;
    if (strpos($val, $from) === false) return $val;
    
    // Check if serialized
    $unserialized = @unserialize($val);
    if ($unserialized !== false || $val === 'b:0;') {
        $replaced = replace_recursive($unserialized, $from, $to);
        return serialize($replaced);
    }
    
    // Check if JSON
    $json = @json_decode($val, true);
    if (json_last_error() === JSON_ERROR_NONE && (is_array($json) || is_object($json))) {
        $replaced = replace_recursive($json, $from, $to);
        return json_encode($replaced, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
    
    return str_replace($from, $to, $val);
}

echo "1. Processing options...\n";
$rows = $wpdb->get_results("SELECT option_id, option_value FROM {$wpdb->options} WHERE option_value LIKE '%$from%'", ARRAY_A);
foreach ($rows as $r) {
    $new_val = process_value($r['option_value'], $from, $to);
    $wpdb->update($wpdb->options, ['option_value' => $new_val], ['option_id' => $r['option_id']]);
}
echo "  Updated " . count($rows) . " options.\n";

echo "2. Processing posts (guid and post_content)...\n";
$wpdb->query("UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '$from', '$to') WHERE post_content LIKE '%$from%'");
$wpdb->query("UPDATE {$wpdb->posts} SET guid = REPLACE(guid, '$from', '$to') WHERE guid LIKE '%$from%'");

echo "3. Processing postmeta...\n";
$rows = $wpdb->get_results("SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_value LIKE '%$from%'", ARRAY_A);
$count = 0;
foreach ($rows as $r) {
    $new_val = process_value($r['meta_value'], $from, $to);
    $wpdb->update($wpdb->postmeta, ['meta_value' => $new_val], ['meta_id' => $r['meta_id']]);
    $count++;
}
echo "  Updated $count postmeta rows.\n";

echo "4. Processing revslider...\n";
$wpdb->query("UPDATE qhaupsn_revslider_slides SET params = REPLACE(params, '$from_esc', '$to_esc'), layers = REPLACE(layers, '$from_esc', '$to_esc')");
$wpdb->query("UPDATE qhaupsn_revslider_slides SET params = REPLACE(params, '$from', '$to'), layers = REPLACE(layers, '$from', '$to')");

echo "Search-replace completed successfully!\n";
