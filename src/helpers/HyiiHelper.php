<?php

namespace bb\helpers;

use bb\helpers\UserHelper as User;
use yii\helpers\ArrayHelper;
use Bb;

Class HyiiHelper {
    /**
     * Checking to make sure a user is an admin is a common task.  This reduces and modularizes code.
     */
    public static function allowAdminOnly()
    {
        if (User::isAdmin() == false) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            echo json_encode([
                "success" => false,
                "message" => "You do not have access to the action!",
            ]);
            exit;

        }
    }

    /**
     * Checking a param value is a common task.  This reduces and modularizes code.
     *
     * @param $param
     * @param $defaultValue
     * @param string $info
     */
    public static function checkParam($param, $defaultValue, $info="")
    {
        if ($param == $defaultValue) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            echo json_encode([
                "success" => false,
                "message" => "Missing Parameter.",
                "Info" => $info
            ]);
            exit;
        }
    }

    /**
     * Loading a post, validating it according the the model rules, and then saving it
     * is a commonly done task.  This reduces and modularizes code.
     *
     * @param $model
     * @return array
     */
    public static function loadAndAddPost($model)
    {
        if ($model->load(static::getPost(), '') && $model->add()) {
            return ["success" => true];
        } else {
            return ["success" => false];
        }
    }

    /**
     * Short way to get the post
     *
     * @return array
     */
    public static function getPost()
    {
        return Bb::$app->request->post();
    }

    /**
     * Shorthand way to return success and nothing else from a model
     *
     * @return array
     */
    public static function successOnly()
    {
        return [
            "success" => true,
        ];
    }

    /**
     * Modularized way to return a failure message
     *
     * @param string $message
     * @return array
     */
    public static function failure($message="")
    {
        return [
            "success" => false,
            "message" => $message
        ];
    }

    /**
     * Returns the passed in data in a way that the quasar q-select can read.
     *
     * @param $data
     * @param $label
     * @param $value
     * @return array
     */
    public static function qSelect($data, $label, $value)
    {
        $prepped = [];
        $index = 0;
        foreach ($data as $d) {
            $prepped[$index]['label'] = $d[$label];
            $prepped[$index]['value'] = $d[$value];
            $index++;
        }
        return $prepped;
    }

    /**
     * Shorthand for
     *
     * @param $data
     * @param $key
     * @return array
     */
    public static function objectify($data, $key)
    {
        return ArrayHelper::index($data, $key);
    }

    /**
     * If a null object was given, spout out an error and halt the application
     *
     * @param $obj
     * @param string $message
     */
    public static function nullCheck($obj, $message="")
    {
        if ($obj === null) {
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
            echo json_encode([
                "success" => false,
                "message" => $message,
            ]);
            exit;
        }
    }

    /**
     * Shorthand easy to remember for doing a straight-up db query
     *
     * @return \yii\db\Query
     */
    public static function query()
    {
        return (new \yii\db\Query());
    }

    /**
     * Checks to see if a post exists based on the specified key existing
     *
     * @param $key
     * @return bool
     */
    public static function isPost($key) : bool
    {
        $post = static::getPost();
        if (isset($post[$key])) {
            return true;
        } else {
            return false;
        }
    }


    public static function getUser()
    {
        return Bb::$app->user->identity;
    }

    /**
     * Finds the single row in the info table and returns the id of that row
     *
     * @return int
     */
    public static function getSystemStateId()
    {
        $info =  static::query()
            ->select("*")
            ->from("{{%info}}")
            ->one();

        return $info['id'];
    }

    public static function getSystemState()
    {
        return static::query()
            ->select("*")
            ->from("{{%info}}")
            ->one();

    }


    /**
     * Finds the general section in the sections table and returns the id of that row
     *
     * @return int
     */
    public static function getGeneralSectionId()
    {
        $generalSection =  static::query()
            ->select("*")
            ->from("{{%sections}}")
            ->one();

        return $generalSection['id'];
    }

    public static function isUserFunctionalityInstalled(): bool
    {
        $infoId = static::getSystemStateId();
        $info = static::query()
            ->select("*")
            ->from("{{%info}}")
            ->where("id = $infoId")
            ->one();

        if ($info["userManagement"] == 'Y') {
            return true;
        } else {
            return false;
        }
    }

    public static function isBlogFunctionalityInstalled(): bool
    {
        $infoId = static::getSystemStateId();
        $info = static::query()
            ->select("*")
            ->from("{{%info}}")
            ->where("id = $infoId")
            ->one();

        if ($info["blog"] == 'Y') {
            return true;
        } else {
            return false;
        }
    }


    public static function isUserLoggedIn()
    {
        Bb::$app->user->enableSession = true;
        $user = UserHelper::loadUserInfo();

        if ($user == null) {
            return false;
        } else {
            return true;
        }
    }

}

