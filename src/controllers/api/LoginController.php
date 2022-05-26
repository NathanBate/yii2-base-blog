<?php

namespace bb\controllers\api;

use Bb;
use bb\helpers\HyiiHelper;
use yii\base\Controller;
use bb\models\api\LoginForm;
use yii\filters\ContentNegotiator;
use yii\web\Response;

class LoginController extends Controller
{

    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config);
        Bb::$app->request->parsers = [ 'application/json' => 'yii\web\JsonParser'];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];
        return $behaviors;
    }

    /**
     * Apps will post to this action to login
     *
     */
    public function actionIndex(): array
    {
        $model = new LoginForm();

        // The $model->load method second parameter is blank because we are not passing in a form name
        if ($model->load(Bb::$app->request->post(),'') && $model->login()) {

            $user = Bb::$app->user->identity;

            /**
             * Prep return data
             */
            $token = $user['authKey'];
            unset($user['authKey']);
            unset($user['password']);

            return [
                "loggedIn" => true,
                "token" => $token,
                "user" => $user,
            ];

        } else {
            return ["loggedIn" => false];
        }
    }

}
