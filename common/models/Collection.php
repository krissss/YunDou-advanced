<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "collection".
 *
 * @property integer $collectionId
 * @property integer $userId
 * @property integer $testLibraryId
 * @property string $createDate
 * @property string $remark
 */
class Collection extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'collection';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'testLibraryId'], 'integer'],
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
            'collectionId' => 'Collection ID',
            'userId' => 'User ID',
            'testLibraryId' => 'Test Library ID',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    /**
     * 查询用户的所有收藏
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllByUser($userId){
        return Collection::find()->where(['userId'=>$userId])->all();
    }

    /**
     * 根据用户查询所有收藏以及与其相关的testLibrary
     * @param $userId
     * @return mixed
     * @throws \Exception
     */
    public static function findAllByUserWithTestLibrary($userId){
        $table_a = Collection::tableName();
        $table_b = TestLibrary::tableName();
        return (new Query())
            ->from([$table_a, $table_b])
            ->where(["$table_a.userId" => $userId])
            ->andWhere("$table_b.testLibraryId = $table_a.testLibraryId")
            ->orderBy(["$table_a.createDate" => SORT_DESC])
            ->all();
    }

    /**
     * 保存或删除一个收藏
     * @param $userId
     * @param $testLibraryId
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public static function saveOrDelete($userId,$testLibraryId){
        $collection = Collection::find()
            ->where(['userId'=>$userId,'testLibraryId'=>$testLibraryId])
            ->one();
        if($collection){
            if(!$collection->delete()){
                throw new Exception("Collection delete error");
            }
            return "delete";
        }else{
            $collection = new Collection();
            $collection->userId = $userId;
            $collection->testLibraryId = $testLibraryId;
            $collection->createDate = DateFunctions::getCurrentDate();
            if(!$collection->save()){
                throw new Exception("Collection save error");
            }
            return "collected";
        }
    }

}
