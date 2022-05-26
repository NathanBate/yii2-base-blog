<?php

namespace bb\controllers\admin;

use Bb;
use bb\base\WebController;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;
use bb\models\LoginForm;
use yii\web\User;

class LoginController extends WebController {

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);

        Bb::$app->user->enableSession = true;
    }

    /**
     * Show a login form and handle its post
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function actionIndex()
    {

        if ((HyiiHelper::isUserLoggedIn()) && (UserHelper::isAdmin())) {
            $this->redirect("/admin/dashboard");
        }

        $model = new LoginForm();

        if (empty($_POST)) {
            return $this->renderTemplate("login/login_form.twig");
        }

        // The $model->load method second parameter is blank because we are not passing in a form name
        if ($model->load(Bb::$app->request->post(),'') && $model->login()) {

            $user = Bb::$app->user->identity;

            $this->redirect("/admin/dashboard");

        } else {
            return $this->renderTemplate("login/login_form.twig", ["error" => "Login Failed"]);
        }
    }

}