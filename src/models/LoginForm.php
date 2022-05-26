<?php

namespace bb\models;

use Bb;
use bb\helpers\HyiiHelper;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property UserModel|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
        ];
    }

    public function validatePassword($password, $hash)
    {
        return Bb::$app->getSecurity()->validatePassword($password, $hash);
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {

            if (getenv('API_LOGIN_ADMIN_ONLY') == 'Y') {

                $user = UserModel::find()
                    ->where(['username' => $this->username])
                    ->andWhere(['active' => 'Y'])
                    ->andWhere(['trashed' => 'N'])
                    ->andWhere("admin <> 'N'")
                    ->one();
            } else {
                $user = UserModel::find()
                    ->where(['username' => $this->username])
                    ->andWhere(['active' => 'Y'])
                    ->andWhere(['trashed' => 'N'])
                    ->one();
            }

            if ($user) {
                if ($this->validatePassword($this->password, $user->password)) {
                    $this->_user = $user;
                    Bb::$app->user->login($user);
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
        return false;
    }



}
