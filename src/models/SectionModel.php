<?php

namespace bb\models;

use Bb;
use yii\db\ActiveRecord;
use bb\helpers\HyiiHelper;

class SectionModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sections}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','slug'], 'required'],
        ];
    }

}