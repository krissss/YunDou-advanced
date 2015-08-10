<?php

namespace common\models;

use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "currenttestlibrary".
 *
 * @property integer $currentTestLibraryId
 * @property integer $userId
 * @property integer $testLibraryId
 * @property integer $testTypeId
 * @property string $remark
 */
class CurrentTestLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'currenttestlibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'testLibraryId', 'testTypeId'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'currentTestLibraryId' => 'Current Test Library ID',
            'userId' => 'User ID',
            'testLibraryId' => 'Test Library ID',
            'testTypeId' => 'Test Type ID',
            'remark' => 'Remark',
        ];
    }

    /**
     * 根据用户和测试类型获取上一次用户做到哪一题，如果第一次做题，使用TestLibrary中类型为testTypeId的第一题
     * @param $user \common\models\Users
     * @param $testTypeId
     * @return int
     */
    public static function findTestLibraryIdByUserAndTestType($user,$testTypeId){
        $next = CurrentTestLibrary::find()
            ->where(['userId'=>$user['userId'],'testTypeId'=>$testTypeId])
            ->one();
        if($next){
            return $next['testLibraryId'];
        }else{
            $first = TestLibrary::findFirstByUserAndTestType($user,$testTypeId);
            return $first['testLibraryId'];
        }
    }

    /**
     * 根据用户id和测试类型查询
     * @param $userId
     * @param $testTypeId
     * @return array|null|\common\models\CurrentTestLibrary
     */
    public static function findByUserAndTestType($userId,$testTypeId){
        return CurrentTestLibrary::find()
            ->where(['userId'=>$userId,'testTypeId'=>$testTypeId])
            ->one();
    }

    /**
     * 重置用户的练习进度，并返回第一题编号
     * @param $user
     * @param $testTypeId
     * @return int
     * @throws Exception
     */
    public static function resetCurrent($user,$testTypeId){
        $testLibrary = TestLibrary::findFirstByUserAndTestType($user,$testTypeId);
        self::saveOrUpdate($user['userId'],$testTypeId,$testLibrary['testLibraryId']);
        return $testLibrary['testLibraryId'];
    }

    /**
     * 存储或更新进度
     * @param $userId
     * @param $testTypeId
     * @param $testLibraryId
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public static function saveOrUpdate($userId, $testTypeId, $testLibraryId){
        //查询用户是否有记录，有则更新，无则插入
        $currentTestLibrary = self::findByUserAndTestType($userId,$testTypeId);
        if($currentTestLibrary){
            //如果上次记录和这次一样则直接跳过，不然update出错
            if($testLibraryId == $currentTestLibrary->testLibraryId){
                return true;
            }
            $currentTestLibrary->testLibraryId = $testLibraryId;
            if($currentTestLibrary->update()){
                return true;
            }else{
                throw new Exception("CurrentTestLibrary update wrong");
            }
        }else{
            $currentTestLibrary = new CurrentTestLibrary();
            $currentTestLibrary->userId = $userId;
            $currentTestLibrary->testLibraryId = $testLibraryId;
            $currentTestLibrary->testTypeId = $testTypeId;
            if($currentTestLibrary->save()){
                return true;
            }else{
                throw new Exception("CurrentTestLibrary save wrong");
            }
        }
    }
}
