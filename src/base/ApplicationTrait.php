<?php

namespace bb\base;

use Bb;
use yii\db\config;


trait ApplicationTrait
{

    private $isApiInstalled;



    /**
     * Returns whether Craft is installed.
     *
     * @return bool
     */
    public function getIsInstalled(): bool
    {
        if ($this->isApiInstalled !== null) {
            return $this->isApiInstalled;
        }

        $infoTable = Bb::$app->db->schema->getTableSchema("{{%info}}");

        if ($infoTable === null) {
            $this->isApiInstalled = false;
        } else {
            $this->isApiInstalled = true;
        }

        return $this->isApiInstalled;

    }


}