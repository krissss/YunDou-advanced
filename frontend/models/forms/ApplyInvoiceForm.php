<?php

namespace frontend\models\forms;

use common\functions\CommonFunctions;
use common\functions\DateFunctions;
use common\models\Invoice;
use common\models\Pay;
use Yii;
use yii\base\Exception;
use yii\base\Model;

class ApplyInvoiceForm extends Model
{
    public $money;
    public $maxMoney;
    public $description;
    public $address;

    public function rules()
    {
        return [
            [['money','address'], 'required'],
            [['money'], 'number'],
            [['money'], 'compare','compareValue'=>$this->maxMoney,'operator' => '<='],
            [['address'], 'string','min'=>8,'max'=>100],
            [['description'], 'string','max'=>100],
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '申请金额',
            'maxMoney' => '可申请',
            'address' => '详细地址',
            'description' => '申请理由（可选）',
        ];
    }

    public function init(){
        $user= Yii::$app->session->get('user');
        $maxMoney = Pay::findSumMoneyByUser($user['userId']);
        if($maxMoney){
            $this->maxMoney = intval($maxMoney);
        }else{
            $this->maxMoney = 0;
        }
    }

    public function record(){
        $user= Yii::$app->session->get('user');
        $invoice = Invoice::findApplyingByUser($user['userId']);
        if($invoice){
            CommonFunctions::createAlertMessage("发票申请失败，您已经有正在申请中的发票了，一次只能申请一张","error");
            return false;
        }else{
            $invoice = new Invoice();
            $invoice->userId = $user['userId'];
            $invoice->address = $this->address;
            $invoice->money = $this->money;
            $invoice->description = $this->description;
            $invoice->createDate = DateFunctions::getCurrentDate();
            $invoice->state = Invoice::STATE_ING;
            if(!$invoice->save()){
                throw new Exception("Apply Invoice save error");
            }
            return true;
        }
    }
}