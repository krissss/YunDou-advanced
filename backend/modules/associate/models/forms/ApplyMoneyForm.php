<?php

namespace backend\modules\associate\models\forms;

use common\models\Users;
use common\models\Withdraw;
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
        $model = Withdraw::find()
            ->where(['userId'=>$user['userId'],'state'=> Withdraw::STATE_APPLYING])->one();
        if($model){
            return false;
        }
        $bitcoin = ($this->money)*100;
        Withdraw::recordOne($user['userId'],$this->money,$bitcoin);
        return true;
    }
}