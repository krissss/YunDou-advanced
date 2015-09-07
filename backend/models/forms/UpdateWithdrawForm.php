<?php
/** 添加或更新在线练习方案的表单 */
namespace backend\models\forms;

use yii;
use common\models\Withdraw;
use yii\base\Exception;
use yii\base\Model;
use common\functions\DateFunctions;

class UpdateWithdrawForm extends Model
{
    public $withdrawId;
    public $nickname;
    public $money;
    public $state;
    public $invoiceMoney;
    public $invoiceNo;
    public $replyContent;


    public function rules()
    {
        return [
            [['withdrawId','nickname','money','state','invoiceMoney','invoiceNo'], 'required'],
            [['invoiceMoney', 'invoiceNo'], 'integer'],
            [['replyContent'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '申请人',
            'invoiceMoney' => '发票金额',
            'invoiceNo' => '发票单号',
            'replyContent' => '回复内容',
            'replyNickname' => '回复人',
            'money' => '提现金额'
        ];
    }

    public  function updateWithdraw()
    {
        $user = Yii::$app->session->get('user');
        $withdraw = Withdraw::findOne($this->withdrawId);
        /** @var $withdraw \common\models\Withdraw */
        $withdraw->invoiceMoney = $this->invoiceMoney;
        $withdraw->invoiceNo = $this->invoiceNo;
        $withdraw->state = $this->state;
        $withdraw->replyContent = $this->replyContent;
        $withdraw->replyDate = DateFunctions::getCurrentDate();
        $withdraw->replyUserId = $user['userId'];
        if (!$withdraw->save()) {
            throw new Exception("withdraw update error");
        }
    }
    public static function initWithId($id,$stateType){
        /** @var $withdraw \common\models\Withdraw */
        $withdraw = Withdraw::findOne($id);
        $form = new UpdateWithdrawForm();
        $form->withdrawId = $id;
        $form->state = $stateType;
        $form->nickname = $withdraw->user['nickname'];
        $form->money = $withdraw->money;
        return $form;
    }
}