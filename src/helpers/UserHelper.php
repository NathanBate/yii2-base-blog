<?php

namespace bb\helpers;

use Bb;
use bb\helpers\HyiiHelper;
use bb\models\UserModel;

Class UserHelper {

    /**
     * Check to see if the logged in user is an admin
     */
    public static function isAdmin() {

        if (Bb::$app->user->identity == null) {
            return false;
        }

        if (Bb::$app->user->identity->admin != "N") {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the user id of the person logged in
     *
     * @return int
     */
    public static function userId()
    {
        return Bb::$app->user->identity->id;
    }
    
    
    public static function  loadUserInfo($id=-1)
    {
        $preppedUserData = [];

        if ($id !== -1) {
            $user = UserModel::findOne($id);
        } else {
            $user = HyiiHelper::getUser();
        }

        if ($user === null) {
            return $user;
        } else {
            $preppedUserData['id'] = $user->id;
            $preppedUserData['firstname'] = $user->firstName;
            $preppedUserData['lastname'] = $user->lastName;
            $preppedUserData['username'] = $user->username;
            $preppedUserData['email'] = $user->email;
            $preppedUserData['admin'] = $user->admin;
            $preppedUserData['active'] = $user->active;

            return $preppedUserData;
        }
    }

    public static function logoutIfNotAdmin($obj)
    {
        if (! static::isAdmin()) {
            $obj->redirect("/logout");
        }
    }

}



