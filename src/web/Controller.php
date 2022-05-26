<?php

namespace bb\web;

use Bb;
use yii\rest\Controller as RestController;

abstract class Controller extends RestController
{

    public function runAction($id, $params = [])
    {

        if (! Bb::$app->getIsInstalled())  {

            /**
             * TODO: JSON response that the API has not been installed
             */
        }

        return parent::runAction($id, $params);
    }

}