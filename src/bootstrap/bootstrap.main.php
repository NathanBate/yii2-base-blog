<?php
/**
 * API Bootstrap File
 */

// Normalize how PHP's string methods (strtoupper, etc) behave.
if (PHP_VERSION_ID < 70300) {
    setlocale(
        LC_CTYPE,
        'C.UTF-8', // libc >= 2.13
        'C.utf8', // different spelling
        'en_US.UTF-8', // fallback to lowest common denominator
        'en_US.utf8' // different spelling for fallback
    );
} else {
    // https://github.com/craftcms/cms/issues/4239
    setlocale(
        LC_CTYPE,
        'C.UTF-8', // libc >= 2.13
        'C.utf8' // different spelling
    );
}

// Set default timezone to UTC
date_default_timezone_set('EST');

// Validate the app type
if (!isset($appType) || ($appType !== 'web' && $appType !== 'console')) {
    throw new Exception('$appType must be set to "web" or "console".');
}

if ($appType === 'console') {
    $devMode = true;
} else if (getenv("ENVIRONMENT") == "dev") {
    $devMode = true;
} else {
    $devMode = false;
}

if ($devMode) {
    ini_set('display_errors', 1);
    defined('YII_DEBUG') || define('YII_DEBUG', true);
    defined('YII_ENV') || define('YII_ENV', 'dev');
} else {
    ini_set('display_errors', 0);
    defined('YII_DEBUG') || define('YII_DEBUG', false);
    defined('YII_ENV') || define('YII_ENV', 'prod');
}

require VENDOR_PATH . DIRECTORY_SEPARATOR .'yiisoft' . DIRECTORY_SEPARATOR . 'yii2' . DIRECTORY_SEPARATOR . 'Yii.php';
require HYII_SRC_PATH . 'Bb.php';


/**
 * Prepare the asset folders
 */
// public
$publicAssetFolderPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . getenv("PUBLIC_FILES_FOLDER") . DIRECTORY_SEPARATOR;
if (getenv("PUBLIC_FILES_FOLDER") != "") {
    if (file_exists($publicAssetFolderPath) !== true) {
        mkdir($publicAssetFolderPath,0775, true);
    }
    Bb::setAlias('@public_asset_folder_path', $publicAssetFolderPath);
}

// secure files
if (STARTER_APP == true) {
    $secureAssetFolderPath = dirname(PUBLIC_DIR) . DIRECTORY_SEPARATOR . getenv("SECURE_FILES_FOLDER") . DIRECTORY_SEPARATOR;
} else {
    $secureAssetFolderPath = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . getenv("SECURE_FILES_FOLDER") . DIRECTORY_SEPARATOR;
}

if (getenv("SECURE_FILES_FOLDER") != "") {
    if (file_exists($secureAssetFolderPath) !== true) {
        mkdir($secureAssetFolderPath);
    }
    Bb::setAlias('@secure_files_folder', $secureAssetFolderPath);
}

if (STARTER_APP == true) {
    $frontendTemplateFolder = APP_TEMPLATES;
} else {
    $frontendTemplateFolder = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "templates" . DIRECTORY_SEPARATOR;
}
Bb::setAlias('@frontendTemplatePath', $frontendTemplateFolder);

Bb::setAlias('@site_url', getenv('BASE_URL'));
Bb::setAlias('@bb', HYII_SRC_PATH);
$config = require CONFIG_FILE;

/**
 * Check to see if there has been an update to the base blog styling if we are in the starter app
 */
if (defined('STARTER_APP') === true) {
    if (STARTER_APP === true) {

        $STARTER_STYLES = PUBLIC_DIR . DIRECTORY_SEPARATOR . 'cpresources' . DIRECTORY_SEPARATOR . 'cp.css';
        $STARTER_JS = PUBLIC_DIR . DIRECTORY_SEPARATOR . 'cpresources' . DIRECTORY_SEPARATOR . 'cp.js';

        $BASE_BLOG_PUBLIC = VENDOR_PATH . DIRECTORY_SEPARATOR . 'nateatnts' . DIRECTORY_SEPARATOR . 'base-blog' . DIRECTORY_SEPARATOR . 'public';
        $BASE_BLOG_STYLES = $BASE_BLOG_PUBLIC . DIRECTORY_SEPARATOR . 'cpresources' . DIRECTORY_SEPARATOR . 'cp.css';
        $BASE_BLOG_JS = $BASE_BLOG_PUBLIC . DIRECTORY_SEPARATOR . 'cpresources' . DIRECTORY_SEPARATOR . 'cp.js';

        /**
         * Older versions will not have this new cpresources folder. If they don't, then create it and copy the styles.
         *
         * If they do have the folder, check to see if the styles need to be updated.
         */
        $STARTER_STYLES_DIR = PUBLIC_DIR . DIRECTORY_SEPARATOR . 'cpresources';
        if (file_exists($STARTER_STYLES_DIR) != true) {
            mkdir($STARTER_STYLES_DIR, 0775, true);
            copy($BASE_BLOG_STYLES, $STARTER_STYLES);
            copy($BASE_BLOG_JS, $STARTER_JS);
        } else {

            if (sha1_file($STARTER_STYLES) != sha1_file($BASE_BLOG_STYLES)) {
                unlink($STARTER_STYLES);
                copy($BASE_BLOG_STYLES, $STARTER_STYLES);
            }

            if (sha1_file($STARTER_JS) != sha1_file($BASE_BLOG_JS)) {
                unlink($STARTER_JS);
                copy($BASE_BLOG_JS, $STARTER_JS);
            }
        }
    }
}

$app = Bb::createObject($config);

return $app;