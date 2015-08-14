<?php

namespace common\models;

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

    public function getUsers(){
        return $this->hasOne(Users::className(), ['userId' => 'userId']);
    }

    public function getPay(){
        return $this->hasOne(Pay::className(), ['userId' => 'userId']);
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

    public function getRemain(){
        $table_a = Invoice::tableName();
        $table_b = Pay::tableName();
        $consume =(new Query())
            ->select('sum(money)')
            ->from($table_a)
            ->where(['userId' => $this->userId])
            ->andWhere(['state' => 'D'])
            ->one();
        $income = (new Query())
            ->select('sum(money)')
            ->from($table_b)
            ->where(['userId' => $this->userId])
            ->one();
        return $income['sum(money)']-$consume['sum(money)'];
    }

    public static function updateNumber($invoiceId,$orderNumber){
        $invoice = Invoice::findOne($invoiceId);
        $invoice->orderNumber = $orderNumber;
//        $invoice->replyUserId = $user['userId'];
//        $invoice->replyDate = DateFunctions::getCurrentDate();
        if(!$invoice->update()){
            throw new Exception("Invoice updateNumber update error!");
        }
    }
}
