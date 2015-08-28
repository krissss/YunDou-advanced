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
 * @property string $createDate
 * @property string $remark
 */
class Money extends \yii\db\ActiveRecord
{
    const TYPE_PAY = 0; //充值
    const TYPE_WITHDRAW = 1;    //提现

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
            [['userId', 'bitcoin','type'], 'integer'],
            [['money'], 'number'],
            [['createDate'], 'safe'],
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
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
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
     * @throws Exception
     */
    public static function recordOne($user,$money,$bitcoin,$type){
        $moneyModel = new Money();   //充值记录
        $moneyModel->userId = $user['userId'];
        $moneyModel->money = $money;
        $moneyModel->type = $type;
        $moneyModel->bitcoin = $bitcoin;
        $moneyModel->createDate = DateFunctions::getCurrentDate();
        if(!$moneyModel->save()){
            throw new Exception("money save error");
        }
        //云豆收入支出记录+用户余额改变
        if($type == Money::TYPE_PAY){   //充值
            IncomeConsume::saveRecord($user['userId'],$bitcoin,Scheme::USAGE_PAY,IncomeConsume::TYPE_INCOME);
            $recommendUser = Users::findRecommendUser($user['recommendUserID']);
            if($recommendUser){ //存在推荐用户
                $rebateScheme = Scheme::findRebateScheme($recommendUser['role']);
                if($money>=$rebateScheme['payMoney']){  //达到当前返点的起始要求
                    $addBitcoin = $bitcoin * $rebateScheme['rebateSelf'];    //返点云豆，返给充值人
                    IncomeConsume::saveRecord($user['userId'],$addBitcoin,$rebateScheme['usageModeId'],IncomeConsume::TYPE_INCOME,$user['userId']);
                    $addBitcoin = $bitcoin * $rebateScheme['rebate'];    //返点云豆，返给推荐人
                    IncomeConsume::saveRecord($recommendUser['userId'],$addBitcoin,$rebateScheme['usageModeId'],IncomeConsume::TYPE_INCOME,$user['userId']);
                }
            }
        }elseif($type == Money::TYPE_WITHDRAW){ //提现
            IncomeConsume::saveRecord($user['userId'],$bitcoin,Scheme::USAGE_WITHDRAW,IncomeConsume::TYPE_CONSUME);
        }else{
            throw new Exception("未知类型");
        }
    }

}
