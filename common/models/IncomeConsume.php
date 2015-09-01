<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "incomeconsume".
 *
 * @property integer $incomeConsumeId
 * @property integer $userId
 * @property integer $bitcoin
 * @property integer $usageModeId
 * @property integer $fromUserId
 * @property string $createDate
 * @property string $type
 * @property string $remark
 */
class IncomeConsume extends \yii\db\ActiveRecord
{
    const TYPE_INCOME = "0";    //云豆收入
    const TYPE_CONSUME = "1";   //云豆支出

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'incomeconsume';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin', 'usageModeId','fromUserId'], 'integer'],
            [['createDate'], 'safe'],
            [['type'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'incomeConsumeId' => 'Income Consume ID',
            'userId' => 'User ID',
            'bitcoin' => 'Bitcoin',
            'usageModeId' => 'Usage Mode ID',
            'fromUserId' => 'From User ID',
            'createDate' => 'Create Date',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public function getUsageMode(){
        return $this->hasOne(UsageMode::className(),['usageModeId'=>'usageModeId']);
    }

    public function getFromUser(){
        return $this->hasOne(Users::className(),['userId'=>'fromUserId']);
    }

    /**
     * 保存一条记录，注意：此处会对应增加或减少用户的云豆数！！
     * @param $userId
     * @param $bitcoin
     * @param $usageModeId
     * @param $type
     * @param $fromUserId
     * @throws Exception
     * @throws \Exception
     */
    public static function saveRecord($userId,$bitcoin,$usageModeId,$type,$fromUserId=null){
        $record = new IncomeConsume();
        $record->userId = $userId;
        $record->bitcoin = $bitcoin;
        $record->usageModeId = $usageModeId;
        $record->type = $type;
        $record->fromUserId = $fromUserId;
        $record->createDate = DateFunctions::getCurrentDate();
        if(!$record->save()){
            throw new Exception("IncomeConsume save error");
        }
        $user = Users::findOne($userId);
        if($type == IncomeConsume::TYPE_INCOME){
            $user->bitcoin += $bitcoin;
        }elseif($type == IncomeConsume::TYPE_CONSUME){
            if($user->bitcoin < $bitcoin){
                throw new Exception("IncomeConsume user bitcoin less than need error");
            }else{
                $user->bitcoin -= $bitcoin;
            }
        }
        if(!$user->update()){
            throw new Exception("IncomeConsume user update error");
        }
    }

    /**
     * 根据用户查询记录
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByUser($userId){
        return IncomeConsume::find()
            ->where(['userId'=>$userId])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询用户一共获得的云豆
     * @param $userId
     * @return mixed
     */
    public static function findTotalIncome($userId){
        $table = IncomeConsume::tableName();
        $money = (new Query())
            ->select('sum(bitcoin)')
            ->from($table)
            ->where(['userId' => $userId,'type'=>IncomeConsume::TYPE_INCOME])
            ->one();
        if($money['sum(bitcoin)']){
            return $money['sum(bitcoin)'];
        }else{
            return 0;
        }
    }

    /**
     * 查询用户一共支出的云豆
     * @param $userId
     * @return mixed
     */
    public static function findTotalConsume($userId){
        $table = IncomeConsume::tableName();
        $money = (new Query())
            ->select('sum(bitcoin)')
            ->from($table)
            ->where(['userId' => $userId,'type'=>IncomeConsume::TYPE_CONSUME])
            ->one();
        if($money['sum(bitcoin)']){
            return $money['sum(bitcoin)'];
        }else{
            return 0;
        }
    }
}
