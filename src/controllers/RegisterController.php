<?php

namespace bb\controllers;

use Bb;
use bb\helpers\HyiiHelper;
use bb\base\WebController;
use bb\models\UserModel;

class RegisterController extends WebController
{
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * Shows the main registration form
     *
     * @return String
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function actionIndex():String
    {

        if (HyiiHelper::isPost("username")) {

            $userRequest = HyiiHelper::getPost();
            $userRequest['admin'] = 'N';
            $userRequest['approved'] = 'N';
            $userRequest['active'] = 'Y';

            $user = new UserModel();
            if ($user->load($userRequest, '') && $user->add()) {
                $this->redirect("/register/thank-you");
            }
        }

        return $this->renderTemplate("register/index.twig");
    }


    public function actionThankYou()
    {
        return $this->renderTemplate("register/thankyou.twig");
    }

}