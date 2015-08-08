<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "testlibrary".
 *
 * @property integer $testLibraryId
 * @property integer $provinceId
 * @property integer $testTypeId
 * @property integer $majorJobId
 * @property integer $preTypeId
 * @property integer $testChapterId
 * @property string $problem
 * @property string $question
 * @property string $options
 * @property string $answer
 * @property string $analysis
 * @property string $picture
 * @property string $score
 * @property string $status
 * @property string $createDate
 * @property integer $createUserId
 * @property string $remark
 */
class TestLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testlibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provinceId', 'testTypeId', 'majorJobId', 'preTypeId', 'testChapterId', 'createUserId'], 'integer'],
            [['createDate'], 'safe'],
            [['problem', 'question', 'options'], 'string', 'max' => 400],
            [['answer'], 'string', 'max' => 10],
            [['analysis', 'picture'], 'string', 'max' => 200],
            [['score'], 'string', 'max' => 20],
            [['status'], 'string', 'max' => 2],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'testLibraryId' => 'Test Library ID',
            'provinceId' => 'Province ID',
            'testTypeId' => 'Test Type ID',
            'majorJobId' => 'Major Job ID',
            'preTypeId' => 'Pre Type ID',
            'testChapterId' => 'Test Chapter ID',
            'problem' => 'Problem',
            'question' => 'Question',
            'options' => 'Options',
            'answer' => 'Answer',
            'analysis' => 'Analysis',
            'picture' => 'Picture',
            'score' => 'Score',
            'status' => 'Status',
            'createDate' => 'Create Date',
            'createUserId' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }

    public function getProvince(){
        return $this->hasOne(Province::className(),['provinceId'=>'provinceId']);
    }

    public function getMajorJob(){
        return $this->hasOne(MajorJob::className(),['majorJobId'=>'majorJobId']);
    }

    public function getTestChapter(){
        return $this->hasOne(TestChapter::className(),['testChapterId'=>'testChapterId']);
    }

    public function getTestType(){
        return $this->hasOne(TestType::className(),['testTypeId'=>'testTypeId']);
    }

    public function getPreType(){
        return $this->hasOne(PreType::className(),['preTypeId'=>'preTypeId']);
    }

    /**
     * 根据用户和测试类型查询第一个试题
     * @param $user \common\models\Users;
     * @param $testTypeId -1表示所有
     * @return \common\models\TestLibrary
     */
    public static function findFirstByUserAndTestType($user,$testTypeId){
        if($testTypeId == -1){
            $testLibrary = TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
                ->asArray()
                ->one();
        }else{
            $testLibrary = TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId])
                ->asArray()
                ->one();
        }
        return $testLibrary;
    }

    /**
     * 根据用户和测试类型查询所有试题
     * @param $user \common\models\Users;
     * @param $testTypeId -1表示所有
     * @return array|\common\models\TestLibrary[]
     */
    public static function findAllByUserAndTestType($user,$testTypeId){
        if($testTypeId == -1){
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
                ->asArray()
                ->all();
        }else{
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId])
                ->asArray()
                ->all();
        }
    }

    public static function findByTemplateDetails($examTemplateDetails,$user){
        $testLibraries = [];
        foreach($examTemplateDetails as $preTypeId => $preTypes) {
            foreach($preTypes as $testTypId => $testTypes){
                foreach($testTypes as $testChapterId => $number){
                    $testLibrary = TestLibrary::find()
                        ->where([
                            'preTypeId'=>$preTypeId,
                            'testTypeId'=>$testTypId,
                            'testChapterId'=>$testChapterId,
                            'provinceId'=>$user['provinceId'],
                            'majorJobId'=>$user['majorJobId']
                        ])
                        ->limit($number)
                        ->orderBy('rand()')
                        ->asArray()
                        ->all();
                    $testLibraries = array_merge($testLibraries,$testLibrary);
                }
            }
        }
        return $testLibraries;
    }
}
