<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setpaper".
 *
 * @property integer $setPaperId
 * @property integer $setPaperTypeId
 * @property string $setPaperName
 * @property string $tips
 * @property string $description
 * @property string $createDate
 * @property integer $createUserID
 * @property string $remark
 */
class SetPaper extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setpaper';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setPaperTypeId', 'createUserID'], 'integer'],
            [['createDate'], 'safe'],
            [['setPaperName'], 'string', 'max' => 50],
            [['tips', 'description'], 'string', 'max' => 200],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setPaperId' => 'Set Paper ID',
            'setPaperTypeId' => 'Set Paper Type ID',
            'setPaperName' => 'Set Paper Name',
            'tips' => 'Tips',
            'description' => 'Description',
            'createDate' => 'Create Date',
            'createUserID' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }
}
