<?php
/** 更新发票处理的表单 */
namespace backend\models\forms;

use common\models\Invoice;
use yii;
use yii\base\Exception;
use yii\base\Model;
use common\functions\DateFunctions;

class UpdateInvoiceForm extends Model
{
    public $invoiceId;
    public $nickname;
    public $money;
    public $replyContent;

    public function rules()
    {
        return [
            [['invoiceId'],'required'],
            [['money'],'number'],
            [['invoiceId'],'integer'],
            [['replyContent'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '申请人',
            'money' => '申请开票金额',
            'replyContent' => '回复内容',
        ];
    }

    public static function initInvoice($id){
        /** @var $invoice \common\models\Invoice */
        $invoice = Invoice::findOne($id);
        $form = new UpdateInvoiceForm();
        $form->invoiceId = $id;
        $form->nickname = $invoice->user['nickname'];
        $form->money = $invoice->money;
        return $form;
    }

    public  function updateInvoice($state){
        $user = Yii::$app->session->get('user');
        $invoice = Invoice::findOne($this->invoiceId);
        /** @var $invoice \common\models\Invoice */
        $invoice->state = $state;
        $invoice->replyContent = $this->replyContent;
        $invoice->replyDate = DateFunctions::getCurrentDate();
        $invoice->replyUserId = $user['userId'];
        if (!$invoice->save()) {
            throw new Exception("invoice update error");
        }
    }
}