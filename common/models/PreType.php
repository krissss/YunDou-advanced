<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pretype".
 *
 * @property integer $preTypeId
 * @property string $name
 * @property string $code
 * @property string $remark
 */
class PreType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pretype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'preTypeId' => 'Pre Type ID',
            'name' => 'Name',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }

    public static function findNameById($preTypeId){
        $result = PreType::getDb()->cache(function() use ($preTypeId){
            return PreType::findOne($preTypeId);
        });
        return $result->name;
    }
}
