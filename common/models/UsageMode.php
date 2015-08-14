<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usagemode".
 *
 * @property integer $usageModeId
 * @property string $usageModeName
 * @property string $type
 * @property string $remark
 */
class UsageMode extends \yii\db\ActiveRecord
{
    const TYPE_INCOME = 0;
    const TYPE_CONSUME = 1;

    public static function tableName()
    {
        return 'usagemode';
    }

    public function rules()
    {
        return [
            [['usageModeName'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'usageModeId' => 'Usage Mode ID',
            'usageModeName' => 'Usage Mode Name',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }

    public function getTypeName(){
        if($this->type == UsageMode::TYPE_INCOME){
            return "收入";
        }elseif($this->type==UsageMode::TYPE_CONSUME){
            return "支出";
        }else{
            return "类型未定义";
        }
    }

    public static function findAllForObject(){
        return UsageMode::find()->all();
    }
}
