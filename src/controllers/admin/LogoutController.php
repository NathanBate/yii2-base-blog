<?php

namespace bb\controllers\admin;

use Bb;
use bb\base\WebController;
use bb\helpers\HyiiHelper;
use bb\models\LoginForm;

class LogoutController extends WebController {

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        Bb::$app->user->enableSession = true;
    }


    public function actionIndex()
    {
        Bb::$app->user->logout();
        $this->redirect("/admin/login");
    }

}