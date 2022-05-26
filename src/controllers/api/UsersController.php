<?php

namespace bb\controllers\api;

use Bb;
use bb\helpers\HyiiHelper;
use bb\models\api\User;
use bb\base\ApiController;

class UsersController extends ApiController
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * Returns a list of users in JSON format
     *
     * @return array
     */
    public function actionIndex()
    {
        return [
            "users" => User::getUsers(),
        ];
    }

    public function actionUpdate() {
        $user = new User();
        if ($user->load(Bb::$app->request->post(), '') && $user->updateUser()) {
            return ["success" => true];
        } else {
            return ["success" => false];
        }
    }


    public function actionAdd()
    {
        $user = new User();

        if ($user->load(Bb::$app->request->post(), '') && $user->add()) {
            return ["success" => true];
        } else {
            return ["success" => false];
        }
    }


    public function actionCheckUsername()
    {
        $bUsernameExists = User::find()
            ->where(["email" => Bb::$app->request->post("email", '')])
            ->count();

        if ($bUsernameExists > 0) {
            return ["usernameUnique" => false];
        }

        return ["usernameUnique" => true];
    }


    public function actionGetUser($id = -1)
    {
        HyiiHelper::checkParam($id, -1, "Missing UserModel Id");
        $user = User::findOne($id);
        HyiiHelper::nullCheck($user, "No Matching UserModel Found.");
        unset($user['password']);
        unset($user['authKey']);
        return $user;
    }

} // class
