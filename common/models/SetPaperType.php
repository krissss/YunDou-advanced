<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setpapertype".
 *
 * @property integer $setPaperTypeId
 * @property string $setPaperTypeName
 * @property string $remark
 */
class SetPaperType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setpapertype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setPaperTypeName'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setPaperTypeId' => 'Set Paper Type ID',
            'setPaperTypeName' => 'Set Paper Type Name',
            'remark' => 'Remark',
        ];
    }
}
