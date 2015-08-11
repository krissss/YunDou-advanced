<?php

namespace common\models;

use frontend\functions\DateFunctions;
use Yii;
use yii\base\Exception;

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

    public static function findAllByUserWithTestLibrary($userId){
        $table_a = Collection::tableName();
        $table_b = TestLibrary::tableName();
        return (new \yii\db\Query())
            ->from([$table_a, $table_b])
            ->where(["$table_a.userId" => $userId])
            ->andWhere("$table_b.testLibraryId = $table_a.testLibraryId")
            ->orderBy(["$table_a.createDate" => SORT_DESC])
            ->all();
    }
}
