<?php

namespace backend\modules\associate\models\forms;

use common\functions\DateFunctions;
use common\models\Users;
use common\models\Withdraw;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ApplyMoneyForm extends Model
{

    public $money;
    public $maxBitcoin;
    public $invoiceMoney;
    public $invoiceNo;

    public function rules()
    {
        return [
            [['money','invoiceMoney', 'invoiceNo'], 'required'],
            [['money'], 'number','min'=>100],
            [['money'], 'compare','compareValue'=>($this->maxBitcoin)/100,'operator' => '<='],
            [['invoiceNo'], 'string','max'=>20],
            [['invoiceMoney'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'money' => '申请金额',
            'maxMoney' => '可申请',
            'invoiceMoney' => '发票金额',
            'invoiceNo' => '发票单号',
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
        $withdraw = new Withdraw();
        $withdraw->userId = $user['userId'];
        $withdraw->money = $this->money;
        $withdraw->bitcoin = $bitcoin;
        $withdraw->invoiceMoney = $this->invoiceMoney;
        $withdraw->invoiceNo = $this->invoiceNo;
        $withdraw->createDate = DateFunctions::getCurrentDate();
        $withdraw->state = Withdraw::STATE_APPLYING;
        if(!$withdraw->save()){
            throw new Exception("Withdraw save error");
        }
        return true;
    }
}