<?php

namespace common\models;

use frontend\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "practicerecord".
 *
 * @property integer $practiceRecordId
 * @property integer $userId
 * @property integer $bitcoin
 * @property string $startDate
 * @property string $endDate
 * @property string $remark
 */
class PracticeRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practicerecord';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'practiceRecordId' => 'Practice Record ID',
            'userId' => 'User ID',
            'bitcoin' => 'Bitcoin',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'remark' => 'Remark',
        ];
    }

    public static function findByUser($userId){
        $currentDate = DateFunctions::getCurrentDate();
        return PracticeRecord::find()
            ->where(['userId'=>$userId])
            ->andWhere(['<=','startDate',$currentDate])
            ->andWhere(['>=','endDate',$currentDate])
            ->one();
    }

    /**
     * 记录支付记录
     * @param $userId
     * @param $scheme \common\models\Scheme
     * @throws Exception
     */
    public static function saveRecord($userId,$scheme){
        $record = new PracticeRecord();
        $record->userId = $userId;
        $record->bitcoin = $scheme['payBitcoin'];
        $record->startDate = DateFunctions::getCurrentDate();
        $record->endDate = date('y-m-d H:i:s',strtotime('+'.$scheme['day'].' day'));
        if(!$record->save()){
            throw new Exception("PracticeRecord save error");
        }
    }
}
