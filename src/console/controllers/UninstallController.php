<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace bb\console\controllers;

use Bb;
use bb\console\Controller;
use bb\helpers\HyiiHelper;
use bb\migrations\Install;
use bb\migrations\InstallBlog;
use bb\migrations\InstallUserManagement;
use yii\db\config;



class UninstallController extends Controller
{
    // public $defaultAction = 'index';

    /**
     * This is only available in dev mode
     */
    public function actionIndex()
    {
        $tableSchema = Bb::$app->db->schema->getTableSchema('{{%info}}');

        if ($tableSchema !== null) {
            $uninstallMigration = new Install();
            $result = $uninstallMigration->safeDown();

            if ($result) {
                Bb::_console("Removal Done! ");
            } else {
                Bb::_console("Removal Failed!");
            }
        } else {
            Bb::_console("No Installation Found");
        }

    }

}
