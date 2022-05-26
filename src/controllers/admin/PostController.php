<?php

namespace bb\controllers\admin;

use Bb;
use bb\base\PrivateWebController;
use bb\helpers\HyiiHelper;
use bb\models\PostModel;
use bb\helpers\UserHelper;

class PostController extends PrivateWebController
{

    /**
     * This method only renders the update page. The updated post hits the API controller.
     *
     * @param $postId
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function actionUpdate($postId)
    {

        if (! UserHelper::isAdmin()) {
            $this->redirect("/logout");
        }

        $this->data['post'] = $post = PostModel::getPost($postId);

        if ($post == null) {
            $this->redirect("/admin/dashboard");
        }

        return $this->renderTemplate("post/update.twig", $this->data);
    }

    public function actionTrash($postId)
    {
        if (! UserHelper::isAdmin()) {
            $this->redirect("/logout");
        }

        $post = PostModel::findOne($postId);

        if ($post != null) {
            $post->trashed = 'Y';
            $post->save();
        }

        $this->redirect("/admin/dashboard");
    }

}