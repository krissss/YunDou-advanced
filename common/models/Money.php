<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "money".
 *
 * @property integer $moneyId
 * @property integer $userId
 * @property string $money
 * @property integer $bitcoin
 * @property integer $type
 * @property integer $from
 * @property string $createDate
 * @property string $agreement
 * @property integer $operateUserId
 * @property string $remark
 */
class Money extends \yii\db\ActiveRecord
{
    const TYPE_PAY = 0; //充值
    const TYPE_WITHDRAW = 1;    //提现

    const FROM_WITHDRAW = 0;    //标识提现
    const FROM_WX = 1;  //微信支付
    const FROM_ZFB = 2; //支付宝支付
    const FROM_XJ = 3;  //现金支付

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'money';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin', 'type', 'from', 'operateUserId'], 'integer'],
            [['money'], 'number'],
            [['createDate'], 'safe'],
            [['agreement'], 'string', 'max' => 40],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'moneyId' => 'Money ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'bitcoin' => 'Bitcoin',
            'type' => 'Type',
            'from' => 'From',
            'createDate' => 'Create Date',
            'agreement' => 'Agreement',
            'operateUserId' => 'Operate User ID',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public function getOperateUser(){
        return $this->hasOne(Users::className(),['userId'=>'operateUserId']);
    }

    public function getFromName(){
        switch($this->from){
            case Money::FROM_WX:$msg = "微信支付";break;
            case Money::FROM_ZFB:$msg = "支付宝支付";break;
            case Money::FROM_XJ:$msg = "现金支付";break;
            case Money::FROM_WITHDRAW:$msg = "提现";break;
            default:$msg = "未定义";break;
        }
        return $msg;
    }

    /**
     * 查询用户的充值记录
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findPayByUser($userId){
        return Money::find()
            ->where(['userId'=>$userId,'type'=>Money::TYPE_PAY])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询用户的提现记录
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findWithdrawByUser($userId){
        return Money::find()
            ->where(['userId'=>$userId,'type'=>Money::TYPE_WITHDRAW])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询用户一共充值的钱
     * @param $userId
     * @return mixed
     */
    public static function findTotalPay($userId){
        $table = Money::tableName();
        $money = (new Query())
            ->select('sum(money)')
            ->from($table)
            ->where(['userId' => $userId,'type'=>Money::TYPE_PAY])
            ->one();
        if($money['sum(money)']){
            return $money['sum(money)'];
        }else{
            return 0;
        }
    }

    /**
     * 查询用户一共提现的钱
     * @param $userId
     * @return mixed
     */
    public static function findTotalWithdraw($userId){
        $table = Money::tableName();
        $money = (new Query())
            ->select('sum(money)')
            ->from($table)
            ->where(['userId' => $userId,'type'=>Money::TYPE_WITHDRAW])
            ->one();
        if($money['sum(money)']){
            return $money['sum(money)'];
        }else{
            return 0;
        }
    }

    /**
     * 查询用户一共充值的获得的云豆
     * @param $userId
     * @return int
     */
    public static function findTotalPayBitcoin($userId){
        $table = Money::tableName();
        $money = (new Query())
            ->select('sum(bitcoin)')
            ->from($table)
            ->where(['userId' => $userId,'type'=>Money::TYPE_PAY])
            ->one();
        if($money['sum(bitcoin)']){
            return $money['sum(bitcoin)'];
        }else{
            return 0;
        }
    }

    /**
     * 获取用户剩余的可以申请发票的钱
     * @param $userId
     * @return mixed
     */
    public static function findRemainMoneyByUser($userId){
        $consume = Invoice::findTotalInvoice($userId);
        $income = Money::findTotalPay($userId);
        return $income-$consume;
    }

    /**
     * 记录用户的充值或提现，包含用户余额的改变和云豆收入支出记录的记录
     * @param $user
     * @param $money
     * @param $bitcoin
     * @param $type
     * @param $from
     * @throws Exception
     */
    public static function recordOne($user,$money,$bitcoin,$type,$from){
        $moneyModel = new Money();
        $moneyModel->userId = $user['userId'];
        $moneyModel->money = $money;
        $moneyModel->type = $type;
        $moneyModel->bitcoin = $bitcoin;
        $moneyModel->createDate = DateFunctions::getCurrentDate();
        $moneyModel->from = $from;
        if(!$moneyModel->save()){
            throw new Exception("money save error");
        }
        //云豆收入支出记录+用户余额改变
        if($type == Money::TYPE_PAY){   //充值
            IncomeConsume::saveRecord($user['userId'],$bitcoin,UsageMode::USAGE_PAY,IncomeConsume::TYPE_INCOME);
            $recommendUser = Users::findRecommendUser($user['recommendUserID']);
            if($recommendUser){ //存在推荐用户
                $rebateScheme = Scheme::findRebateScheme($recommendUser['role']);
                if($rebateScheme && $money>=$rebateScheme['payMoney']){  //存在返点方案，并且达到当前返点的起始要求
                    $addBitcoin = $bitcoin * $rebateScheme['rebateSelf'];    //返点云豆，返给充值人
                    IncomeConsume::saveRecord($user['userId'],$addBitcoin,$rebateScheme['usageModeId'],IncomeConsume::TYPE_INCOME,$user['userId']);
                    $addBitcoin = $bitcoin * $rebateScheme['rebate'];    //返点云豆，返给推荐人
                    IncomeConsume::saveRecord($recommendUser['userId'],$addBitcoin,$rebateScheme['usageModeId'],IncomeConsume::TYPE_INCOME,$user['userId']);
                }
            }
        }elseif($type == Money::TYPE_WITHDRAW){ //提现
            IncomeConsume::saveRecord($user['userId'],$bitcoin,UsageMode::USAGE_WITHDRAW,IncomeConsume::TYPE_CONSUME);
        }else{
            throw new Exception("未知类型");
        }
    }

}
