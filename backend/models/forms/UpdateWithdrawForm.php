<?php
/** 添加或更新在线练习方案的表单 */
namespace backend\models\forms;

use common\models\Money;
use common\models\Scheme;
use common\models\UsageMode;
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
    public $invoiceMoney;
    public $invoiceNo;
    public $replyContent;
    public $maxMoney;


    public function rules()
    {
        return [
            [['withdrawId'],'required'],
            [['withdrawId'],'integer'],
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
            'money' => '提现金额',
            'maxMoney' => '最大可提现金额'
        ];
    }

    public static function initWithId($id){
        /** @var $withdraw \common\models\Withdraw */
        $withdraw = Withdraw::findOne($id);
        $form = new UpdateWithdrawForm();
        $form->withdrawId = $id;
        $form->nickname = $withdraw->user['nickname'];
        $form->money = $withdraw->money;
        $form->invoiceNo = $withdraw->invoiceNo;
        $form->invoiceMoney = $withdraw->invoiceMoney;
        $form->maxMoney = Scheme::calculateWithdrawMaxMoney($withdraw->user);
        return $form;
    }

    public  function updateWithdraw($state){
        $user = Yii::$app->session->get('user');
        $withdraw = Withdraw::findOne($this->withdrawId);
        /** @var $withdraw \common\models\Withdraw */
        $withdraw->state = $state;
        $withdraw->replyContent = $this->replyContent;
        $withdraw->replyDate = DateFunctions::getCurrentDate();
        $withdraw->replyUserId = $user['userId'];
        if (!$withdraw->save()) {
            throw new Exception("withdraw update error");
        }
        //如果是同意申请，记录money和incomeConsume和users表的bitcoin变化
        if($state==Withdraw::STATE_PASS) {
            $user = $withdraw->user;
            $money = $withdraw->money;
            $bitcoin = $withdraw->bitcoin;
            Money::recordOne($user,$money,$bitcoin,UsageMode::TYPE_CONSUME,Money::FROM_WITHDRAW);
        }
    }

}