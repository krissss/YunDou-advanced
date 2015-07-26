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
     * @param $userId
     * @param $testTypeId
     * @return mixed
     */
    public static function findTestLibraryIdByUserAndTestType($userId,$testTypeId){
        $next = CurrentTestLibrary::find()
            ->where(['userId'=>$userId,'testTypeId'=>$testTypeId])
            ->one();
        if($next){
            return $next['testLibraryId'];
        }else{
            $first = TestLibrary::find()
                ->where(['testTypeId'=>$testTypeId])
                ->one();
            return $first['testLibraryId'];
        }
    }

    public static function findByUserAndTestType($userId,$testTypeId){
        return CurrentTestLibrary::find()
            ->where(['userId'=>$userId,'testTypeId'=>$testTypeId])
            ->one();
    }

    public static function saveOrUpdate($userId, $testTypeId, $testLibraryId){
        //查询用户是否有上次记录，有则更新，无则插入
        $next = self::findByUserAndTestType($userId,$testTypeId);
        if($next){
            //如果上次记录和这次一样则直接跳过，不然update出错
            if($testLibraryId == $next->testLibraryId){
                return true;
            }
            $next->testLibraryId = $testLibraryId;
            if($next->update()){
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
