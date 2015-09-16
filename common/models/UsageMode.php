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

    //务必保持和usageMode表一致
    const USAGE_PRACTICE = 2;   //在线练习支出
    const USAGE_PAY = 1;    //充值收入
    const USAGE_COURSE = 3; //在线课堂支出
    const USAGE_REBATE_A = 4; //A级用户充值返点收入
    const USAGE_SHARE = 5; //考试通过分享收入
    const USAGE_SHOP = 6; //商城购物支出
    const USAGE_WITHDRAW = 7; //提现支出
    const USAGE_REBATE_AA = 8; //AA级用户充值返点收入
    const USAGE_REBATE_AAA = 9; //AAA级用户充值返点收入
    const USAGE_DISTRIBUTE_INCOME = 10; //大客户分配收入
    const USAGE_DISTRIBUTE_CONSUME = 11; //大客户分配支出
    const USAGE_REBATE_BIG = 12; //大客户充值返点收入
    const USAGE_WITHDRAW_AA = 13; //金牌伙伴提现支出
    const USAGE_WITHDRAW_AAA_LOW = 14; //钻石伙伴提现支出-低比例
    const USAGE_WITHDRAW_AAA_HIGH = 15; //钻石伙伴提现支出-高比例

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
