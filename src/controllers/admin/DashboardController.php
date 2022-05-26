<?php

namespace bb\controllers\admin;

use Bb;
use bb\base\PrivateWebController;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;

class DashboardController extends PrivateWebController {

    public function actionIndex() {

        if (! UserHelper::isAdmin()) {
            $this->redirect("/");
        }

        if (UserHelper::isAdmin()) {
            $this->data['posts'] = HyiiHelper::query()
                ->select("*")
                ->from("{{%posts}}")
                ->where("trashed ='N'")
                ->orderBy("date DESC")
                ->all();
        }

        return $this->renderTemplate("dashboard/dashboard.twig", $this->data);
    }

}