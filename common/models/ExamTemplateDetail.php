<?php

namespace common\models;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "examtemplatedetail".
 *
 * @property integer $examTemplateDetailId
 * @property integer $examTemplateId
 * @property integer $testTypeId
 * @property integer $preTypeId
 * @property integer $testChapterId
 * @property integer $testNumber
 * @property string $remark
 */
class ExamTemplateDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examtemplatedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examTemplateId', 'testTypeId', 'preTypeId', 'testChapterId', 'testNumber'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examTemplateDetailId' => 'Exam Template Detail ID',
            'examTemplateId' => 'Exam Template ID',
            'testTypeId' => 'Test Type ID',
            'preTypeId' => 'Pre Type ID',
            'testChapterId' => 'Test Chapter ID',
            'testNumber' => 'Test Number',
            'remark' => 'Remark',
        ];
    }

    public static function saveOne($examTemplateId,$preTypeId,$testChapterId,$testTypeId,$testNumber){
        $examTemplateDetail = new ExamTemplateDetail();
        $examTemplateDetail->examTemplateId = $examTemplateId;
        $examTemplateDetail->preTypeId = $preTypeId;
        $examTemplateDetail->testChapterId = $testChapterId;
        $examTemplateDetail->testTypeId = $testTypeId;
        $examTemplateDetail->testNumber = $testNumber;
        if(!$examTemplateDetail->save()){
            throw new Exception($testChapterId."保存失败");
        }
    }

    public static function findByExamTemplate($examTemplateId){
        return ExamTemplateDetail::find()
            ->where(['examTemplateId'=>$examTemplateId])
            ->asArray()
            ->all();
    }

    /**
     * 重新构造数组
     * @param $examTemplateDetails
     * @return array
     */
    public static function remakeArray($examTemplateDetails){
        $newDetails = [
            1=>[    //专业基础
                1=>[],  //单选
                2=>[],  //多选
                3=>[],  //判断
                4=>[]   //案例
            ],
            2=>[    //管理实务
                1=>[],  //单选
                2=>[],  //多选
                3=>[],  //判断
                4=>[]   //案例
            ]
        ];
        foreach($examTemplateDetails as $examTemplateDetail){
            $preTypeId = $examTemplateDetail['preTypeId'];
            $testTypeId = $examTemplateDetail['testTypeId'];
            $arr = [
                'testChapter' => $examTemplateDetail['testChapterId'],
                'testNumber' => $examTemplateDetail['testNumber']
            ];
            array_push($newDetails[$preTypeId][$testTypeId],$arr);
        }
        for($i=1; $i<3; $i++){
            for($j=1 ;$j<5; $j++){
                //构造成：章节为key，测试题数为value
                $newDetails[$i][$j] = ArrayHelper::map($newDetails[$i][$j], 'testChapter', 'testNumber');
            }
        }
        return $newDetails;
    }

    public static function deleteAllByExamTemplateId($examTemplateId){
        ExamTemplateDetail::deleteAll(['examTemplateId'=>$examTemplateId]);
    }
}
