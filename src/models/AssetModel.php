<?php

namespace bb\models;

use Bb;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class AssetModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%assets}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [];
    }



}