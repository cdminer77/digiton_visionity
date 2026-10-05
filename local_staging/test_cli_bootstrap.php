<?php
define('DUPXABSPATH', __DIR__ . '/dup-installer');
define('DUPX_VERSION', '5.0.5');
define('DUPX_INIT', str_replace('\\', '/', __DIR__ . '/dup-installer'));
define('DUPX_ROOT', str_replace('\\', '/', __DIR__));

require_once(DUPX_INIT . '/src/Utils/Autoloader.php');
Duplicator\Installer\Utils\Autoloader::register();

use Duplicator\Installer\Core\Bootstrap;
use Duplicator\Installer\Core\Params\PrmMng;
use Duplicator\Installer\Utils\Log\Log;

Bootstrap::init();

echo "Bootstrap successful!\n";

$params = PrmMng::getInstance();
echo "Archive Engine: " . $params->getValue(PrmMng::PARAM_ARCHIVE_ENGINE) . "\n";
echo "Archive Path: " . $params->getValue(PrmMng::PARAM_ARCHIVE_PATH) . "\n";
echo "Path New: " . $params->getValue(PrmMng::PARAM_PATH_NEW) . "\n";
echo "URL New: " . $params->getValue(PrmMng::PARAM_URL_NEW) . "\n";
echo "DB Host: " . $params->getValue(PrmMng::PARAM_DB_HOST) . "\n";
echo "DB Name: " . $params->getValue(PrmMng::PARAM_DB_NAME) . "\n";
