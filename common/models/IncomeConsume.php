<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "incomeconsume".
 *
 * @property integer $incomeConsumeId
 * @property integer $userId
 * @property integer $bitcoin
 * @property integer $usageModeId
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
            [['userId', 'bitcoin', 'usageModeId'], 'integer'],
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

    /**
     * 保存一条记录，注意：此处会对应增加或减少用户的云豆数！！
     * @param $userId
     * @param $bitcoin
     * @param $usageModeId
     * @param $type
     * @throws Exception
     * @throws \Exception
     */
    public static function saveRecord($userId,$bitcoin,$usageModeId,$type){
        $record = new IncomeConsume();
        $record->userId = $userId;
        $record->bitcoin = $bitcoin;
        $record->usageModeId = $usageModeId;
        $record->type = $type;
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
}
