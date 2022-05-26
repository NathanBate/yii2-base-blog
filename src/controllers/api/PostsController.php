<?php

namespace bb\controllers\api;

use Bb;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;
use bb\models\api\Post;
use bb\base\ApiController;
use bb\models\PostModel;
use bb\models\PostElementsModel;

class PostsController extends ApiController
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /*

    public function actionIndex()
    {
        return [
            "success" => true,
            "posts" => Post::getPosts(),
        ];

    }


    public function actionAdd()
    {
        $post = new Post();

        if ($post->load(Bb::$app->request->post(), '') && $post->add()) {
            return [
                "success" => true,
                "id" => $post->id,
            ];
        } else {
            return ["success" => false];
        }
    }

    public function actionGetPost($id = -1)
    {
        HyiiHelper::checkParam($id, -1, "Missing Post ID");
        $post = Post::findOne($id);
        HyiiHelper::nullCheck($post, "Unable to find the specified post");


        return [
            "success" => true,
            "title" => $post->title,
        ];
    }


    */

    /**
     * The vuejs web front end update post uses this api function; however, this api function turns on sessions for this
     * call and makes sure the user is logged in.
     *
     * @param $postId
     * @return array
     */
    public function actionUpdate($postId)
    {
        if (HyiiHelper::isUserLoggedIn() == false) {
            return [
                "success" => false,
                "action" => "logout"
            ];
        } else {

            if (PostModel::updatePost($postId)) {
                return [
                    "success" => true,
                ];
            } else {
                return [
                    "success" => false,
                    "message" => "Update Failed"
                ];
            }

        }

    } // function action update


    public function actionTrashElement()
    {

        if (HyiiHelper::isUserLoggedIn() == false) {
            return [
                "success" => false,
                "action" => "logout"
            ];
        } else {

            $post = HyiiHelper::getPost();

            if (isset($post['elementId'])) {
                $element = PostElementsModel::findOne($post['elementId']);


                if ($element != null) {
                    $element->trashed = 'Y';
                    $element->save();

                    /**
                     * reorder the elements starting with 0
                     */
                    $elements = HyiiHelper::query()
                        ->select("*")
                        ->from("{{%post_elements}}")
                        ->where("trashed = 'N'")
                        ->andWhere("post = " . $post['postId'])
                        ->orderBy("order ASC")
                        ->all();

                    if ($elements != null) {
                        $i = 0;
                        foreach ($elements as $element) {
                            $e = PostElementsModel::findOne($element['id']);
                            if ($e != null) {
                                $e->order = $i;
                                $e->save();
                                $i++;
                            }
                        }
                    }

                    return ["success" => true];
                }


            }
        }
        /**
         * if I get here, something went wrong
         */
        return ["success" => false];
    }

    public function actionGetPost($postId)
    {
        if (HyiiHelper::isUserLoggedIn() == false) {
            return [
                "success" => false,
                "action" => "logout"
            ];
        } else {
            return [ "success" => true, "data" => PostModel::getPost($postId)];
        }

    }

}