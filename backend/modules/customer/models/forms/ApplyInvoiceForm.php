<?php

namespace backend\modules\customer\models\forms;

use common\functions\DateFunctions;
use common\models\Invoice;
use common\models\Money;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ApplyInvoiceForm extends Model
{
    public $money;
    public $remainMoney;
    public $address;

    public function rules()
    {
        return [
            [['money','address'], 'required'],
            [['money'],'number', 'min'=>50 ],
            [['money'], 'compare','compareValue'=>($this->remainMoney),'operator' => '<='],
            [['address'],'string']
        ];
    }

    public function attributeLabels(){
        return [
            'money' => '申请开票金额 （元）',
            'remainMoney' => '未开发票金额（元）',
            'address' => '详细地址'
        ];
    }

    public function init(){
        $user= Yii::$app->session->get('user');
        $remainMoney = Money::findRemainMoneyByUser($user['userId']);
        if($remainMoney){
            $this->remainMoney =$remainMoney ;
        }else{
            $this->maxInvoiceMoney = 0;
        }
         $address = $user['address'];
        $this->address =$address;
    }

    public function record(){
        $user = Yii::$app->session->get('user');
        $invoice = Invoice::findApplyingByUser($user['userId']);
        if($invoice){
            return false;
        }
        $invoice = new Invoice();
        $user = Yii::$app->session->get('user');
        $invoice->userId = $user['userId'];
        $invoice->money = $this->money;
        $invoice->address = $user['address'];
        $invoice->createDate = DateFunctions::getCurrentDate();
        $invoice->state = Invoice::STATE_ING;
        $invoice->address = $this->address;
        if(!$invoice->save()){
            throw new Exception("Invoice save error");
        }
        return true;
    }
}