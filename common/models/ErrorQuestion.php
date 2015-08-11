<?php

namespace common\models;

use frontend\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "errorquestion".
 *
 * @property integer $errorQuestionId
 * @property integer $userId
 * @property integer $testLibraryId
 * @property string $userAnswer
 * @property string $createDate
 * @property string $remark
 */
class ErrorQuestion extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'errorquestion';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'testLibraryId'], 'integer'],
            [['createDate'], 'safe'],
            [['userAnswer'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'errorQuestionId' => 'Error Question ID',
            'userId' => 'User ID',
            'testLibraryId' => 'Test Library ID',
            'userAnswer' => 'User Answer',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    public function getTestLibrary(){
        return $this->hasOne(TestLibrary::className(),['testLibraryId'=>'testLibraryId']);
    }

    /**
     * @param $userId
     * @param $testLibraryId
     * @return null|\common\models\ErrorQuestion
     */
    public static function findByUserAndTestLibraryId($userId,$testLibraryId){
        return ErrorQuestion::find()
            ->where(['userId'=>$userId, 'testLibraryId'=>$testLibraryId])
            ->one();
    }

    public static function saveOrUpdate($userId,$testLibraryId){
        //查询用户是否有错误记录，有则更新记录时间，无则插入
        $errorQuestion = self::findByUserAndTestLibraryId($userId,$testLibraryId);
        if($errorQuestion){
            $errorQuestion->createDate = DateFunctions::getCurrentDate();
            if($errorQuestion->update()){
                return true;
            }else{
                throw new Exception('ErrorQuestion update wrong');
            }
        }else{
            $errorQuestion = new ErrorQuestion();
            $errorQuestion->userId = $userId;
            $errorQuestion->testLibraryId = $testLibraryId;
            $errorQuestion->createDate = DateFunctions::getCurrentDate();
            if($errorQuestion->save()){
                return true;
            }else{
                throw new Exception('ErrorQuestion save wrong');
            }
        }
    }

    public static function findAllByUserWithTestLibrary($userId){
        $table_a = ErrorQuestion::tableName();
        $table_b = TestLibrary::tableName();
        return (new \yii\db\Query())
            ->from([$table_a,$table_b])
            ->where(["$table_a.userId"=>$userId])
            ->andWhere("$table_b.testLibraryId = $table_a.testLibraryId")
            ->orderBy(["$table_a.createDate"=>SORT_DESC])
            ->all();
    }
}
