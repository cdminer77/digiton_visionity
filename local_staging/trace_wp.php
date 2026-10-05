<?php
function t($msg) {
    echo "[" . date("H:i:s") . "] " . $msg . "\n";
    flush();
}

t("1. Defining constants");
define('WP_USE_THEMES', true);

t("2. Requiring wp-load.php");
require __DIR__ . '/wp-load.php';

t("3. Loaded wp-load.php. Current user / DB ready.");
t("4. Calling wp()");
wp();

t("5. Requiring template-loader.php");
require ABSPATH . WPINC . '/template-loader.php';

t("6. Finished completely!");
