<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoiceId
 * @property integer $userId
 * @property string $money
 * @property string $description
 * @property string $address
 * @property string $createDate
 * @property string $state
 * @property integer $replyUserId
 * @property string $replyContent
 * @property string $orderNumber
 * @property string $replyDate
 * @property string $remark
 */
class Invoice extends \yii\db\ActiveRecord
{
    const STATE_ING = 'A';
    const STATE_PASS = 'B';
    const STATE_REFUSE = 'C';
    const STATE_OVER = 'D';

    public static function tableName()
    {
        return 'invoice';
    }

    public function rules()
    {
        return [
            [['userId', 'replyUserId'], 'integer'],
            [['money'], 'number'],
            [['createDate', 'replyDate'], 'safe'],
            [['description', 'replyContent'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 60],
            [['state'], 'string', 'max' => 1],
            [['orderNumber'], 'string', 'max' => 30],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceId' => 'Invoice ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'description' => 'Description',
            'address' => 'Address',
            'createDate' => 'Create Date',
            'state' => 'State',
            'replyUserId' => 'Reply User ID',
            'replyContent' => 'Reply Content',
            'orderNumber' => 'Order Number',
            'replyDate' => 'Reply Date',
            'remark' => 'Remark',
        ];
    }

    public function getUser(){
        return $this->hasOne(Users::className(), ['userId' => 'userId']);
    }

    public function getReplyUser(){
        return $this->hasOne(Users::className(), ['userId' => 'replyUserId']);
    }

    public function getPay(){
        return $this->hasOne(Money::className(), ['userId' => 'userId']);
    }

    public function getStateName(){
        switch($this->state) {
            case Invoice::STATE_ING: $content="申请中";break;
            case Invoice::STATE_PASS: $content="管理员允许";break;
            case Invoice::STATE_REFUSE: $content="管理员拒绝";break;
            case Invoice::STATE_OVER: $content="已完成配送";break;
            default :  $content="状态未定义";
        }
        return $content;
    }

    /**
     * 查询用户一共开票完成的数目
     * @param $userId
     * @return mixed
     */
    public static function findTotalInvoice($userId){
        $table = Invoice::tableName();
        $money = (new Query())
            ->select('sum(money)')
            ->from($table)
            ->where(['userId' => $userId])
            ->andWhere(['state' => 'D'])
            ->one();
        if($money['sum(money)']){
            return $money['sum(money)'];
        }else{
            return 0;
        }
    }

    /**
     * 填写快递单号
     * @param $invoiceId
     * @param $orderNumber
     * @throws Exception
     * @throws \Exception
     */
    public static function updateOrderNumber($invoiceId,$orderNumber){
        $invoice = Invoice::findOne($invoiceId);
        $invoice->orderNumber = $orderNumber;
        $invoice->state = Invoice::STATE_OVER;
        if(!$invoice->update()){
            throw new Exception("Invoice updateNumber update error!");
        }
    }

    /**
     * 查询用户的所有发票
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllByUser($userId){
        return Invoice::find()
            ->where(['userId'=>$userId])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询用户正在申请中的发票
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findApplyingByUser($userId){
        return Invoice::find()
            ->where(['userId'=>$userId])
            ->andWhere(['state'=>Invoice::STATE_ING])
            ->orWhere(['state'=>Invoice::STATE_PASS])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    public static function changeState($invoiceId,$state,$replyContent){
        $user = Yii::$app->session->get('user');
        $invoice = Invoice::findOne($invoiceId);
        $invoice->state = $state;
        $invoice->replyContent = $replyContent;
        $invoice->replyDate = DateFunctions::getCurrentDate();
        $invoice->replyUserId = $user['userId'];
        if(!$invoice->update()){
            throw new Exception("Invoice update error");
        }
    }
}
