<?php

namespace bb\models;

use yii\db\ActiveRecord;
use bb\helpers\HyiiHelper;

class InfoModel extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sections}}';
    }

}