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
 * @property string $pa1
 * @property string $pa2
 * @property string $pa3
 * @property string $pa4
 * @property string $pb1
 * @property string $pb2
 * @property string $pb3
 * @property string $pb4
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
            [['pa1', 'pa2', 'pa3', 'pa4', 'pb1', 'pb2', 'pb3', 'pb4'], 'string', 'max' => 8],
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
            'majorJobId' => 'Major Job ID',
            'name' => 'Name',
            'createDate' => 'Create Date',
            'createUserId' => 'Create User ID',
            'state' => 'State',
            'pa1' => 'Pa1',
            'pa2' => 'Pa2',
            'pa3' => 'Pa3',
            'pa4' => 'Pa4',
            'pb1' => 'Pb1',
            'pb2' => 'Pb2',
            'pb3' => 'Pb3',
            'pb4' => 'Pb4',
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
        $number_pa1 = intval(explode('|',$this->pa1)[0]);
        $number_pa2 = intval(explode('|',$this->pa2)[0]);
        $number_pa3 = intval(explode('|',$this->pa3)[0]);
        $number_pa4 = intval(explode('|',$this->pa4)[0]);
        $number_pb1 = intval(explode('|',$this->pb1)[0]);
        $number_pb2 = intval(explode('|',$this->pb2)[0]);
        $number_pb3 = intval(explode('|',$this->pb3)[0]);
        $number_pb4 = intval(explode('|',$this->pb4)[0]);
        return $number_pa1 + $number_pa2 + $number_pa3 + $number_pa4
             + $number_pb1 + $number_pb2 + $number_pb3 + $number_pb4;
    }

    public function getQuestionScore(){
        $score_pa1 = intval(explode('|',$this->pa1)[1]);
        $score_pa2 = intval(explode('|',$this->pa2)[1]);
        $score_pa3 = intval(explode('|',$this->pa3)[1]);
        $score_pa4 = intval(explode('|',$this->pa4)[1]);
        $score_pb1 = intval(explode('|',$this->pb1)[1]);
        $score_pb2 = intval(explode('|',$this->pb2)[1]);
        $score_pb3 = intval(explode('|',$this->pb3)[1]);
        $score_pb4 = intval(explode('|',$this->pb4)[1]);
        return $score_pa1 + $score_pa2 + $score_pa3 + $score_pa4
             + $score_pb1 + $score_pb2 + $score_pb3 + $score_pb4;
    }

    public static function saveOne($provinceId, $majorJobId, $name, $createUserId){
        $examTemplate = new ExamTemplate();
        $examTemplate->name = $name;
        $examTemplate->provinceId = $provinceId;
        $examTemplate->majorJobId = $majorJobId;
        $examTemplate->createUserId = $createUserId;
        $examTemplate->pa1 = "0|0";
        $examTemplate->pa2 = "0|0";
        $examTemplate->pa3 = "0|0";
        $examTemplate->pa4 = "0|0";
        $examTemplate->pb1 = "0|0";
        $examTemplate->pb2 = "0|0";
        $examTemplate->pb3 = "0|0";
        $examTemplate->pb4 = "0|0";
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
