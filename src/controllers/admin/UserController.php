<?php

namespace bb\controllers\admin;

use Bb;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;
use bb\models\UserModel;
use bb\base\PrivateWebController;
use yii\base\BaseObject;

class UserController extends PrivateWebController
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }


    public function actionAdd()
    {
        UserHelper::logoutIfNotAdmin($this);

        $user = new UserModel();

        if (HyiiHelper::isPost("firstName")) {

            //Bb::dd($_POST);

            if ($user->load(Bb::$app->request->post(), '') && $user->add()) {
                $this->redirect("/admin/user/list");
            }
        } else {
            $this->data['user']['id'] = "";
            $this->data['user']['firstname'] = "";
            $this->data['user']['lastname'] = "";
            $this->data['user']['username'] = "";
            $this->data['user']['email'] = "";
            $this->data['user']['admin'] = "";
            $this->data['user']['active'] = "";
            return $this->renderTemplate("users/add.twig", $this->data);
        }

    }

    public function actionUpdate($id)
    {
        UserHelper::logoutIfNotAdmin($this);

        $this->updateUser($id);

        $user = UserHelper::loadUserInfo($id);

        //Bb::dd($user);
        if ($user !== null) {
            $this->data['user'] = $user;
            return $this->renderTemplate("users/update.twig", $this->data);
        } else {

            /**
             * Since the user was not found, redirect back to the user list
             */
            $this->redirect("/admin/user/list");
        }
    }


    public function actionCheckUsername()
    {
        Bb::$app->request->parsers = [ 'application/json' => 'yii\web\JsonParser'];
        $bUsernameExists = UserModel::find()
            ->where(["username" => Bb::$app->request->post("username", '')])
            ->count();

        if ($bUsernameExists > 0) {
            return json_encode([
                    "success" => true,
                    "message" => "not-unique"
                ]);
        }

        return json_encode([
            "success" => true,
            "message" => "unique"
        ]);
    }

    public function actionProfile() 
    {

        //Bb::dd($this->data);

        if (HyiiHelper::isPost("firstName")) {
            $post = HyiiHelper::getPost();
            $id = $post['id'];
            $this->updateUser($id);
            $this->data['userUpdated'] = true;
            $this->data['userUpdateStatus'] = true;
            $this->data['user'] = UserHelper::loadUserInfo($id);

        } else {
            $this->data['user'] = UserHelper::loadUserInfo();
        }

        return $this->renderTemplate("users/profile.twig", $this->data);
    }

    /**
     * Gives a list of users as well as the opportunity to add a new user
     */
    public function actionList()
    {
        UserHelper::logoutIfNotAdmin($this);

        /**
         * Get unapproved users
         */
        $this->data['unapproved_users'] = UserModel::getUsers('N');

        /**
         * Get approved users
         */
        $this->data['users'] = $users = UserModel::getUsers();

        return $this->renderTemplate("users/list.twig", $this->data);

    }


    public function actionApproval($type, $id)
    {
        UserHelper::logoutIfNotAdmin($this);

        $user = UserModel::findOne($id);

        if ($user !== null) {
            if ($type === "yes") {
                $user->approved = 'Y';
            } else {
                $user->approved = 'D';
            }
            $user->save();
        }
        $this->redirect("/admin/user/list");
    }

    private function updateUser($id)
    {

        /**
         * Check to see if there is a post and handle it if there is
         */
        if (HyiiHelper::isPost('id')) {
            $currentUser = UserModel::findOne($id);
            $updatedUser = HyiiHelper::getPost();

            if (isset($updatedUser['prevent_autofill'])) {
                unset($updatedUser['prevent_autofill']);
                unset($updatedUser['password_fake']);
            }

            if (isset($updatedUser['password_no_auto_complete'])) {
                $updatedUser['password'] = $updatedUser['password_no_auto_complete'];
                unset($updatedUser['password_no_auto_complete']);
            }

            if (isset($updatedUser['password'])) {
                if ($updatedUser['password'] != "") {
                    $updatedUser['password'] = Bb::$app->getSecurity()->generatePasswordHash($updatedUser['password']);
                } else {
                    unset($updatedUser['password']);
                }
            }
            $currentUser->attributes = $updatedUser;
            $currentUser->save();
            $this->data['userUpdated'] = true;

        }
    }

} // class
