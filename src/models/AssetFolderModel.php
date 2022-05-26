<?php

namespace bb\models;

use Bb;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class AssetFolderModel extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%asset_folders}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section','folderName'], 'required'],
        ];
    }

}