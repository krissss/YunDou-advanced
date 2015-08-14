<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "examtemplate".
 *
 * @property integer $examTemplateId
 * @property integer $provinceId
 * @property integer $majorJobId
 * @property string $name
 * @property string $createDate
 * @property integer $createUserId
 * @property string $state
 * @property string $remark
 */
class ExamTemplate extends \yii\db\ActiveRecord
{
    const STATE_ABLE = 'T';
    const STATE_DISABLE = 'F';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examtemplate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provinceId', 'majorJobId', 'createUserId'], 'integer'],
            [['createDate'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['state'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examTemplateId' => 'Exam Template ID',
            'provinceId' => 'Province ID',
            'marjorJobId' => 'Marjor Job ID',
            'name' => 'Name',
            'createDate' => 'Create Date',
            'createUserId' => 'Create User ID',
            'state' => 'State',
            'remark' => 'Remark',
        ];
    }

    public function getMajorJob(){
        return $this->hasOne(MajorJob::className(),['majorJobId'=>'majorJobId']);
    }

    public function getProvince(){
        return $this->hasOne(Province::className(),['provinceId'=>'provinceId']);
    }

    public function getCreateUser(){
        return $this->hasOne(Users::className(),['userId'=>'createUserId']);
    }

    public function getStateName(){
        if($this->state == ExamTemplate::STATE_ABLE){
            return "已启用";
        }elseif($this->state == ExamTemplate::STATE_DISABLE){
            return "未启用";
        }else{
            return "状态不明";
        }
    }

    public function getQuestionCount(){
        $examTemplateDetails = ExamTemplateDetail::findByExamTemplate($this->examTemplateId);
        $number = 0;
        foreach($examTemplateDetails as $examTemplateDetail){
            $number += $examTemplateDetail['testNumber'];
        }
        return $number;
    }

    public static function saveOne($provinceId, $majorJobId, $name, $createUserId){
        $examTemplate = new ExamTemplate();
        $examTemplate->name = $name;
        $examTemplate->provinceId = $provinceId;
        $examTemplate->majorJobId = $majorJobId;
        $examTemplate->createUserId = $createUserId;
        $examTemplate->createDate = DateFunctions::getCurrentDate();
        $examTemplate->state = ExamTemplate::STATE_DISABLE;
        if(!$examTemplate->save()){
            throw new Exception("模拟试卷模板保存错误");
        }
        return $examTemplate->examTemplateId;
    }

    public static function findByMajorJobAndProvince($majorJobId,$provinceId){
        return ExamTemplate::find()
            ->where(['majorJobId'=>$majorJobId, 'provinceId'=>$provinceId,'state'=>ExamTemplate::STATE_ABLE])
            ->asArray()
            ->all();
    }

    public static function updateState($examTemplateId,$newState){
        $examTemplate = ExamTemplate::findOne($examTemplateId);
        if(!$examTemplate){
            throw new Exception("ExamTemplate id not exist");
        }
        $examTemplate->state = $newState;
        if(!$examTemplate->update()){
            throw new Exception("ExamTemplate update error");
        }
    }
}
