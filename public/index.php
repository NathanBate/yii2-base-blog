<?php
/**
 * API Console Bootstrap File
 */

// Set path constants
define('HYII_SRC_PATH',dirname(__DIR__) . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR);
define('VENDOR_PATH', __DIR__ . '/../vendor');
define('CONFIG_FILE', HYII_SRC_PATH . DIRECTORY_SEPARATOR .  "config" . DIRECTORY_SEPARATOR . "web.config.php");
const PUBLIC_DIR = __DIR__;
const STARTER_APP = false;

// Load Composer's autoloader
require_once VENDOR_PATH.'/autoload.php';


// Load dotenv?
if (class_exists('Dotenv\Dotenv') && file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();
} else {
    exit("Either the .env file does not exist, or the Dotenv class file does not exist.  Exiting.");
}

$app = require __DIR__ . "/../src/bootstrap/web.bootstrap.php";
$exitCode = $app->run();
exit($exitCode);