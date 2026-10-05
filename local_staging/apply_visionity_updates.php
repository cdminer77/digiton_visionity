<?php
require_once __DIR__ . '/wp-load.php';
global $wpdb;

echo "=== 1. BACKUP HOMEPAGE ELEMENTOR DATA ===\n";
$current_home_data = file_get_contents(__DIR__ . '/home_elementor_data_backup.json');

$map = json_decode(file_get_contents(__DIR__ . '/imported_photos_map.json'), true);

function get_img_info($filename, $map) {
    $id = $map[$filename] ?? 0;
    if (!$id) return null;
    $url = wp_get_attachment_url($id);
    return ['id' => $id, 'url' => $url];
}

$photo1 = get_img_info("Verso l'orizzonte della citta (1).png", $map);
$photo2 = get_img_info("Riunione dirigenziale con vista sulla citta (2).png", $map);
$photo3 = get_img_info("Riunione dirigenziale in ufficio moderno (3).png", $map);
$photo4 = get_img_info("Ufficio al tramonto con skyline urbano (4).png", $map);
$photo5 = get_img_info("Conversazione in lounge aziendale moderno (5).png", $map);
$photo6 = get_img_info("Corridoio futuristico verso lo skyline urbano (6).png", $map);
$photo7 = get_img_info("Networking nell'elegante lounge panoramica (7).png", $map);
$photo8 = get_img_info("Portale di luce sulla citta (8).png", $map);
$photo9 = get_img_info("Strategia esecutiva sulla scrivania marmorea (9).png", $map);

echo "=== 2. UPDATING SLIDER REVOLUTION (slider-1) ===\n";
// Slide 1 -> Photo 1
if ($photo1) {
    $s1 = $wpdb->get_row("SELECT id, layers FROM qhaupsn_revslider_slides WHERE id = 1", ARRAY_A);
    if ($s1) {
        $layers = json_decode($s1['layers'], true);
        if (isset($layers[2])) {
            $layers[2]['media']['imageUrl'] = $photo1['url'];
            $layers[2]['media']['imageId'] = $photo1['id'];
        }
        $wpdb->update('qhaupsn_revslider_slides', ['layers' => json_encode($layers)], ['id' => 1]);
        echo "Slide 1 updated with Photo 1 ({$photo1['url']})\n";
    }
}

// Slide 2 -> Photo 2
if ($photo2) {
    $s2 = $wpdb->get_row("SELECT id, layers FROM qhaupsn_revslider_slides WHERE id = 2", ARRAY_A);
    if ($s2) {
        $layers = json_decode($s2['layers'], true);
        if (isset($layers[2])) {
            $layers[2]['media']['imageUrl'] = $photo2['url'];
            $layers[2]['media']['imageId'] = $photo2['id'];
        }
        $wpdb->update('qhaupsn_revslider_slides', ['layers' => json_encode($layers)], ['id' => 2]);
        echo "Slide 2 updated with Photo 2 ({$photo2['url']})\n";
    }
}

// Slide 3 -> Photo 3 + Fix text
if ($photo3) {
    $s3 = $wpdb->get_row("SELECT id, layers FROM qhaupsn_revslider_slides WHERE id = 3", ARRAY_A);
    if ($s3) {
        $layers = json_decode($s3['layers'], true);
        if (isset($layers[2])) {
            $layers[2]['media']['imageUrl'] = $photo3['url'];
            $layers[2]['media']['imageId'] = $photo3['id'];
        }
        // Fix text layer
        if (isset($layers[0])) {
            $layers[0]['text'] = "Exclusive Strategic Network. We connect ambitious companies to elite leadership and growth.";
        }
        $wpdb->update('qhaupsn_revslider_slides', ['layers' => json_encode($layers)], ['id' => 3]);
        echo "Slide 3 updated with Photo 3 and Visionity text!\n";
    }
}

echo "=== 3. UPDATING HOMEPAGE SECTIONS IN ELEMENTOR ===\n";
$elements = json_decode($current_home_data, true);

// Filter out Section 5 (fake testimonials: id 96168f2) and Section 9 (fake blog: id adedd4c)
$new_elements = [];
foreach ($elements as $sec) {
    $secId = $sec['id'] ?? '';
    if ($secId === '96168f2') {
        echo "  [REMOVED] Section $secId (Fake testimonials / Why leaders choose Visionity)\n";
        continue;
    }
    if ($secId === 'adedd4c') {
        echo "  [REMOVED] Section $secId (Fake blog / Latest news)\n";
        continue;
    }
    
    // Check inside section for images to replace
    // 1. Section 2 (b056889) -> Column 0e408b8 background_image
    if ($secId === 'b056889' && $photo4) {
        foreach ($sec['elements'] as &$c1) {
            foreach ($c1['elements'] as &$innerSec) {
                if (($innerSec['id'] ?? '') === 'f210c62') {
                    foreach ($innerSec['elements'] as &$innerCol) {
                        if (($innerCol['id'] ?? '') === '0e408b8') {
                            $innerCol['settings']['background_image'] = [
                                'url' => $photo4['url'],
                                'id' => $photo4['id']
                            ];
                            echo "  [UPDATED] Section 2 column background -> Photo 4\n";
                        }
                    }
                }
            }
        }
    }
    
    // 2. Section 3 (d587454) -> Image widgets fbec427 and bb898d0
    if ($secId === 'd587454') {
        foreach ($sec['elements'] as &$col) {
            foreach ($col['elements'] as &$w) {
                if (($w['id'] ?? '') === 'fbec427' && $photo6) {
                    $w['settings']['image'] = [
                        'url' => $photo6['url'],
                        'id' => $photo6['id']
                    ];
                    echo "  [UPDATED] Section 3 Image 1 -> Photo 6\n";
                }
                if (($w['id'] ?? '') === 'bb898d0' && $photo7) {
                    $w['settings']['image'] = [
                        'url' => $photo7['url'],
                        'id' => $photo7['id']
                    ];
                    echo "  [UPDATED] Section 3 Image 2 -> Photo 7\n";
                }
            }
        }
    }
    
    // 3. Section 7 (d19fd16) -> Image widget d342d97 (FAQ image)
    if ($secId === 'd19fd16' && $photo8) {
        foreach ($sec['elements'] as &$col) {
            foreach ($col['elements'] as &$w) {
                if (($w['id'] ?? '') === 'd342d97') {
                    $w['settings']['image'] = [
                        'url' => $photo8['url'],
                        'id' => $photo8['id']
                    ];
                    echo "  [UPDATED] Section 7 FAQ Image -> Photo 8\n";
                }
            }
        }
    }
    
    $new_elements[] = $sec;
}

$updated_json = json_encode($new_elements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
update_post_meta(5002, '_elementor_data', wp_slash($updated_json));
echo "Homepage Elementor data updated with wp_slash in postmeta!\n";

echo "=== 4. UPDATING STICKY HEADER BACKGROUND & CONTRAST ===\n";
// In header layout 37587, set sticky header section (2d2d535) background to solid dark #15171c with 0.95 opacity
// and ensure logo is high contrast
$header_meta = get_post_meta(37587, '_elementor_data', true);
file_put_contents(__DIR__ . '/header_layout_backup.json', $header_meta);
$header_elements = json_decode($header_meta, true);

foreach ($header_elements as &$hsec) {
    if (($hsec['id'] ?? '') === '2d2d535') {
        // Sticky Desktop section
        $hsec['settings']['background_background'] = 'classic';
        $hsec['settings']['background_color'] = 'rgba(21, 23, 28, 0.96)';
        echo "  [UPDATED] Sticky header desktop background set to solid dark (rgba(21, 23, 28, 0.96))\n";
    }
    if (($hsec['id'] ?? '') === '3518727') {
        // Sticky Mobile section
        $hsec['settings']['background_background'] = 'classic';
        $hsec['settings']['background_color'] = 'rgba(21, 23, 28, 0.96)';
        echo "  [UPDATED] Sticky header mobile background set to solid dark (rgba(21, 23, 28, 0.96))\n";
    }
}
update_post_meta(37587, '_elementor_data', json_encode($header_elements, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
echo "Header layout 37587 updated!\n";

echo "=== 5. CLEARING ELEMENTOR CACHE ===\n";
if (class_exists('\Elementor\Plugin')) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    echo "Elementor cache files cleared!\n";
}

echo "=== ALL UPDATES APPLIED SUCCESSFULLY! ===\n";
