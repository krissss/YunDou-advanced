<?php

namespace backend\modules\associate\models\forms;

use common\functions\DateFunctions;
use common\models\Scheme;
use common\models\Withdraw;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ApplyMoneyForm extends Model
{

    public $money;
    public $maxMoney;
    public $invoiceMoney;
    public $invoiceNo;

    public $schemes;    //提现方案，数组或对象

    public function rules()
    {
        return [
            [['money','invoiceMoney', 'invoiceNo'], 'required'],
            [['money'], 'integer','min'=>100],
            [['money'], 'compare','compareValue'=>($this->maxMoney),'operator' => '<='],
            [['invoiceNo'], 'string','max'=>20],
            [['invoiceMoney'], 'number'],
        ];
    }

    public function attributeLabels(){
        return [
            'money' => '申请金额',
            'maxMoney' => '最大可申请金额（元）',
            'invoiceMoney' => '发票金额',
            'invoiceNo' => '发票单号',
        ];
    }

    public function init(){
        $user= Yii::$app->session->get('user');
        $this->maxMoney = Scheme::calculateWithdrawMaxMoney($user);
    }

    public function record(){
        $user = Yii::$app->session->get('user');
        $model = Withdraw::find()
            ->where(['userId'=>$user['userId'],'state'=> Withdraw::STATE_APPLYING])->one();
        if($model){
            return false;
        }
        $withdraw = new Withdraw();
        $withdraw->userId = $user['userId'];
        $withdraw->money = $this->money;
        $withdraw->bitcoin = ceil(Scheme::calculateWithdrawBitcoin($user,$this->money));    //扣除云豆数为向上取整,有小数就整数部分加1
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