<?php

namespace bb\web;

use Bb;
use bb\base\ApplicationTrait;

class Application extends \yii\web\Application
{
    use ApplicationTrait;

    const EVENT_INIT = 'init';


    public function __construct($config = [])
    {
        Bb::$app = $this;
        parent::__construct($config);
    }

    public function init()
    {
        parent::init();
    }


    public function bootstrap()
    {
        // Ensure that the request component has been instantiated
        if (!$this->has('request', true)) {
            $this->getRequest();
        }

        parent::bootstrap();
    }

}