<?php

namespace common\models;

use Yii;
use yii\caching\DbDependency;
use yii\db\Query;

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
 * @property string $pictureSmall
 * @property string $pictureBig
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
            [['analysis', 'pictureSmall','pictureBig'], 'string', 'max' => 200],
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
            'pictureSmall' => 'Picture Small',
            'pictureBig' => 'Picture Big',
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
            $dependency = new DbDependency([
                'sql'=> 'select count(*) from testlibrary where provinceId='.$user['provinceId'].' and majorJobId='.$user['majorJobId']
            ]);
            $result = Collection::getDb()->cache(function () use ($user) {
                return TestLibrary::find()
                    ->where(['provinceId' => $user['provinceId'], 'majorJobId' => $user['majorJobId']])
                    ->asArray()
                    ->all();
            },null,$dependency);
        }else{
            $dependency = new DbDependency([
                'sql'=> 'select count(*) from testlibrary where provinceId='.$user['provinceId'].' and majorJobId='.$user['majorJobId'].' and testTypeId='.$testTypeId
            ]);
            $result = Collection::getDb()->cache(function () use ($user,$testTypeId) {
                return TestLibrary::find()
                    ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId])
                    ->asArray()
                    ->all();
            },null,$dependency);
        }
        return $result;

    }

    /**
     * 根据模板查题
     * @param $examTemplateDetails
     * @param $user
     * @return array
     */
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

    /**
     * 查询用户当前做到哪种题型的第几题
     * @param $user
     * @param $testTypeId
     * @return mixed
     */
    public static function findCurrentNumber($user,$testTypeId){
        $current = CurrentTestLibrary::findByUserAndTestType($user['userId'],$testTypeId);
        if(!$current){
            return 0;
        }
        $table = TestLibrary::tableName();
        if($testTypeId == -1){
            $subQuery = (new Query())->select('testLibraryId')
                ->from($table)
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']]);
        }else{
            $subQuery = (new Query())->select('testLibraryId')
                ->from($table)
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId]);
        }
        $query = (new Query())->select('count(*)')->from([$subQuery])->where(['<=','testLibraryId',$current['testLibraryId']]);
        $result = $query->one();
        return $result['count(*)'];
    }

    /**
     * 查询总题数
     * @param $user
     * @param $testTypeId
     * @return int|string
     */
    public static function findTotalNumber($user,$testTypeId){
        if($testTypeId == -1){
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
                ->count();
        }else{
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId])
                ->count();
        }
    }

    /**
     * 根据用户查询一定数量的题目
     * @param $user
     * @param $testTypeId
     * @param $limit
     * @param $offset
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findByUserAndTestType($user,$testTypeId,$limit,$offset){
        if($testTypeId == -1){
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId']])
                ->limit($limit)
                ->offset($offset)
                ->asArray()
                ->all();
        }else{
            return TestLibrary::find()
                ->where(['provinceId'=>$user['provinceId'],'majorJobId'=>$user['majorJobId'],'testTypeId'=>$testTypeId])
                ->limit($limit)
                ->offset($offset)
                ->asArray()
                ->all();
        }
    }
}
