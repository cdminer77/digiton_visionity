<?php
require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

$sourceDir = dirname(__DIR__) . '/downloaded_photos';
$files = scandir($sourceDir);

$uploaded = [];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    $filePath = "$sourceDir/$file";
    if (!is_file($filePath)) continue;
    
    // Check if attachment already exists with this title
    $title = preg_replace('/\.[^.]+$/', '', $file);
    $existing = get_page_by_title($title, OBJECT, 'attachment');
    if ($existing) {
        echo "Already exists: {$file} (ID: {$existing->ID})\n";
        $uploaded[$file] = $existing->ID;
        continue;
    }
    
    // Copy to uploads
    $upload = wp_upload_bits($file, null, file_get_contents($filePath));
    if (!empty($upload['error'])) {
        echo "Error uploading {$file}: {$upload['error']}\n";
        continue;
    }
    
    $wp_filetype = wp_check_filetype($file, null);
    $attachment = [
        'post_mime_type' => $wp_filetype['type'],
        'post_title'     => sanitize_file_name($title),
        'post_content'   => '',
        'post_status'    => 'inherit'
    ];
    
    $attach_id = wp_insert_attachment($attachment, $upload['file']);
    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);
    
    echo "Imported {$file} -> Attachment ID: {$attach_id} (URL: {$upload['url']})\n";
    $uploaded[$file] = $attach_id;
}

file_put_contents(__DIR__ . '/imported_photos_map.json', json_encode($uploaded, JSON_PRETTY_PRINT));
echo "Finished importing photos!\n";
