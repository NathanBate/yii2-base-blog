<?php

namespace bb\controllers;

use Bb;
use bb\base\WebController;
use bb\base\PrivateWebController;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;
use bb\models\LoginForm;
use bb\models\PostModel;
use yii\helpers\BaseUrl;

class SiteController extends WebController
{
    protected $reqestedUrl;
    protected $user;

    public function __construct($id, $module, $config = [])
    {
        $this->reqestedUrl = $this->data['returnUrl'] = BaseUrl::base(false) . Bb::$app->request->url;

        Bb::$app->user->enableSession = true;
        $this->user = UserHelper::loadUserInfo();
        $this->data['user'] = $this->reqestedUrl;

        if ($this->user == null) {
            $this->data['displayLoginForm'] = true;
        }

        $this->template_dir = "app";

        parent::__construct($id, $module, $config);
    }

    public function actionIndex()
    {
        $this->data['posts'] = HyiiHelper::query()
            ->select("*")
            ->from("{{%posts}}")
            ->where("trashed = 'N'")
            ->andWhere("published = 'Y'")
            ->orderBy("Date DESC")
            ->all();

        $this->data['template'] = "index.twig";
        return $this->renderTemplate("index.twig", $this->data);
    }


    public function actionPost($postId=-1) {

        if ($postId == -1) {
            $this->redirect("/");
        } else {

            $post = PostModel::getPost($postId, true);

            if ($post == null) {
                $this->redirect("/");
            } else {

                $this->data['post'] = $post;
                $this->data['template'] = "post.twig";
                return $this->renderTemplate("post.twig", $this->data);
            }
        }

    }

    public function actionAbout()
    {
        $this->data['template'] = "about.twig";
        return $this->renderTemplate("about.twig", $this->data);
    }

    public function actionLogin()
    {

        if (HyiiHelper::isUserLoggedIn()) {
            $this->redirect("/");
        }

        $model = new LoginForm();

        /**
         * check to see if there is a post
         */
        if (HyiiHelper::isPost('returnUrl')) {

            /**
             * put the post in an easily accessible variable
             */
            $post = HyiiHelper::getPost();

            /**
             * check to see if the post is sharing a template to use. if it isn't, then use the login template
             */
            $template = "";
            if (isset($post['template'])) {
                $template = $post['template'];
            }

            if ($template != "") {
                $template = $post['template'];
            } else {
                $template = "login.twig";
            }

            /**
             * Attempt to log in the user.
             */
            if ($model->load(Bb::$app->request->post(),'') && $model->login()) {
                //return "login successful";
                $this->redirect($post['returnUrl']);
            } else {

                //return "login failed";
                $this->data["error"] = "Login Failed";
                return $this->renderTemplate($template, $this->data);
            }

        } else {

            /**
             * Since there was not a login post, then just display the login form.
             */
            $this->data['template'] = "_partials/login_form.twig";
             $this->data['returnUrl'] = $this->data['site_url'];
            return $this->renderTemplate("login.twig", $this->data);
        }

    } // function login


    public function actionLogout()
    {
        Bb::$app->user->logout();
        $this->redirect("/");

    }

}