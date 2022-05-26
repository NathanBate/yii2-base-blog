<?php

namespace bb\models;

use yii\db\ActiveRecord;
use bb\helpers\HyiiHelper;

class PostElementsModel extends ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%post_elements}}';
    }


}