<?php

namespace bb\models\api;

use Bb;
use yii\db\ActiveRecord;
use bb\helpers\HyiiHelper;
use bb\helpers\UserHelper;

class Post extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            ['section','default', 'value' => HyiiHelper::getGeneralSectionId()],
            ['author','default', 'value' => UserHelper::userId()],
        ];
    }

    public function add()
    {
        if ($this->validate()) {
            $this->save();
            return true;
        } else {
            return false;
        }
    }


    public static function getPosts()
    {
        return HyiiHelper::query()
            ->select("*")
            ->from("{{%posts}}")
            ->where("trashed='N'")
            ->all();
    }

}