<?php

namespace backend\modules\associate\models\forms;

use common\models\Money;
use common\models\Users;
use Yii;
use yii\base\Model;

class ApplyMoneyForm extends Model
{

    public $money;
    public $maxBitcoin;

    public function rules()
    {
        return [
            [['money'], 'required'],
            [['money'], 'number','min'=>50],
            [['money'], 'compare','compareValue'=>($this->maxBitcoin)/100,'operator' => '<=']
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '申请金额',
            'maxMoney' => '可申请'
        ];
    }

    public function init(){
        $user= Yii::$app->session->get('user');
        $maxBitcoin = Users::findBitcoin($user['userId']);
        if($maxBitcoin){
            $this->maxBitcoin = intval($maxBitcoin);
        }else{
            $this->maxBitcoin = 0;
        }
    }

    public function record(){
        $user = Yii::$app->session->get('user');
        $bitcoin = ($this->money)*100;
        Money::recordOne($user,$this->money,$bitcoin,Money::TYPE_WITHDRAW);
    }
}