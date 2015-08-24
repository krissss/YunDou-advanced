<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;

/**
 * This is the model class for table "examscore".
 *
 * @property integer $examScoreId
 * @property integer $userId
 * @property string $score
 * @property string $totalScore
 * @property integer $majorJobId
 * @property integer $provinceId
 * @property string $createDate
 * @property string $remark
 */
class ExamScore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examscore';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'majorJobId', 'provinceId'], 'integer'],
            [['score', 'totalScore'], 'number'],
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
            'examScoreId' => 'Exam Score ID',
            'userId' => 'User ID',
            'score' => 'Score',
            'totalScore' => 'Total Score',
            'majorJobId' => 'Major Job ID',
            'provinceId' => 'Province ID',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    /**
     * 记录模拟考试信息
     * @param $finalScore
     * @param $totalScore
     */
    public static function recordOne($finalScore,$totalScore){
        $user = Yii::$app->session->get('user');
        $record = new ExamScore();
        $record->userId = $user['userId'];
        $record->provinceId = $user['provinceId'];
        $record->majorJobId = $user['majorJobId'];
        $record->score = $finalScore;
        $record->totalScore = $totalScore;
        $record->createDate = DateFunctions::getCurrentDate();
        $record->save();
    }

    /**
     * 查询比自己成绩低的人数
     * @param $finalScore
     * @param $totalScore //保留字段，以后可以算  score/totalScore  的比值
     * @return int|mixed
     */
    public static function findRank($finalScore,$totalScore){
        $user = Yii::$app->session->get('user');
        $result = ExamScore::find()
            ->select('count(*)')
            ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
            ->andWhere(['<','score',$finalScore])
            ->asArray()
            ->one();
        if($result){
            return $result['count(*)'];
        }else{
            return 0;
        }
    }

    public static function findTotalRank(){
        $user = Yii::$app->session->get('user');
        $result = ExamScore::find()
            ->select('count(*)')
            ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
            ->asArray()
            ->one();
        if($result){
            return $result['count(*)'];
        }else{
            return 0;
        }
    }
}
