<?php

namespace common\models;

use Yii;
use yii\db\Query;

/**
 * This is the model class for table "withdraw".
 *
 * @property integer $withdrawId
 * @property integer $userId
 * @property string $money
 * @property integer $bitcoin
 * @property string $description
 * @property string $invoiceMoney
 * @property string $invoiceNo
 * @property string $createDate
 * @property integer $replyUserId
 * @property string $replyDate
 * @property string $replyContent
 * @property integer $state
 * @property string $remark
 */
class Withdraw extends \yii\db\ActiveRecord
{
    const STATE_APPLYING = 0;
    const STATE_REFUSE = 1;
    const STATE_PASS = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin',  'replyUserId', 'state'], 'integer'],
            [['money', 'invoiceMoney'], 'number'],
            [['createDate', 'replyDate'], 'safe'],
            [['description', 'replyContent'], 'string', 'max' => 50],
            [['invoiceNo'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'withdrawId' => 'Withdraw ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'bitcoin' => 'Bitcoin',
            'description' => 'Description',
            'invoiceMoney' => 'Invoice Money',
            'invoiceNo' => 'Invoice No',
            'createDate' => 'Create Date',
            'replyUserId' => 'Reply User ID',
            'replyDate' => 'Reply Date',
            'replyContent' => 'Reply Content',
            'state' => 'State',
            'remark' => 'Remark',
        ];
    }

    public function getReplyUser(){
        return $this->hasOne(Users::className(),['userId'=>'replyUserId']);
    }

    public function getUser(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public function getStateName(){
        switch($this->state) {
            case Withdraw::STATE_APPLYING: $content="申请中";break;
            case Withdraw::STATE_PASS: $content="管理员允许";break;
            case Withdraw::STATE_REFUSE: $content="管理员拒绝";break;
            default :  $content="状态未定义";
        }
        return $content;
    }

    /**
     * 查询一个用户的总提现金额
     * @param $userId
     * @return int
     */
    public static function findTotalMoney($userId){
        $table = Withdraw::tableName();
        $money = (new Query())
            ->select('sum(money)')
            ->from($table)
            ->where(['userId'=>$userId,'state'=>Withdraw::STATE_PASS])
            ->one();
        if($money['sum(money)']){
            return $money['sum(money)'];
        }else{
            return 0;
        }
    }

    /**
     * 统计所有用户的提现金额
     * @return int
     */
    public static function findTotalUsersMoney(){
        $table = Withdraw::tableName();
        $money = (new Query())
            ->select('sum(money)')
            ->from($table)
            ->where(['state'=>Withdraw::STATE_PASS])
            ->one();
        if($money['sum(money)']){
            return $money['sum(money)'];
        }else{
            return 0;
        }
    }

}
