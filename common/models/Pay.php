<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "pay".
 *
 * @property integer $payId
 * @property integer $userId
 * @property string $money
 * @property integer $bitcoin
 * @property string $createDate
 * @property string $remark
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin'], 'integer'],
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
            'payId' => 'Pay ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'bitcoin' => 'Bitcoin',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public static function findByUser($userId){
        return Pay::find()
            ->where(['userId'=>$userId])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    public static function findRemainMoneyByUser($userId){
        $table_a = Invoice::tableName();
        $table_b = Pay::tableName();
        $consume =(new Query())
            ->select('sum(money)')
            ->from($table_a)
            ->where(['userId' => $userId])
            ->andWhere(['state' => 'D'])
            ->one();
        $income = (new Query())
            ->select('sum(money)')
            ->from($table_b)
            ->where(['userId' => $userId])
            ->one();
        return $income['sum(money)']-$consume['sum(money)'];
    }

    public static function recordOne(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $scheme = $session->get('scheme');
        $money = $session->get('money');
        $pay = new Pay();   //充值记录
        $pay->userId = $user['userId'];
        $pay->money = $money;
        $addBitcoin = intval($money)*intval($scheme['getBitcoin'])/intval($scheme['payMoney']);
        $pay->bitcoin = $addBitcoin;
        $pay->createDate = DateFunctions::getCurrentDate();
        if(!$pay->save()){
            throw new Exception("pay save error");
        }
        //云豆收入支出记录+用户余额改变
        IncomeConsume::saveRecord($user['userId'],$addBitcoin,Scheme::USAGE_PAY,IncomeConsume::TYPE_INCOME);
        $recommendUser = Users::findRecommendUser($user['recommendUserId']);
        if($recommendUser){ //存在推荐用户
            $rebateScheme = Scheme::findRebateScheme();
            if($money>=$rebateScheme['payMoney']){  //达到当前返点的起始要求
                $addBitcoin = $addBitcoin * $rebateScheme['rebate'];    //返点云豆
                IncomeConsume::saveRecord($recommendUser['userId'],$addBitcoin,Scheme::USAGE_REBATE,IncomeConsume::TYPE_INCOME,$user['userId']);
            }
        }
        $session->remove('scheme');
        $session->remove('money');
    }

}
