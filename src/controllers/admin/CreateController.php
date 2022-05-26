<?php

namespace bb\controllers\admin;

use Bb;
use bb\base\PrivateWebController;
use bb\models\api\Post;
use bb\models\SectionModel;
use bb\models\PostModel;
use bb\helpers\HyiiHelper;

class CreateController extends PrivateWebController {


    public function actionPost()
    {

        /**
         * If there is a post, create a new blog post
         */
        if (HyiiHelper::isPost("title")) {
            $post = HyiiHelper::getPost();
            $blogPost = new PostModel();
            $blogPost->title = $post['title'];
            $blogPost->section = $post['section'];
            $blogPost->save();
            $newPostId = $blogPost->id;
            $this->redirect("/admin/post/update/$newPostId");
        }

        /**
         * Get the default section
         */
        $systemState = HyiiHelper::getSystemState();
        $section = SectionModel::findOne($systemState['defaultSection']);

        $this->data['section']['title'] = $section->title;
        $this->data['section']['id'] = $section->id;

        return $this->renderTemplate("create/post.twig", $this->data);
    }


}