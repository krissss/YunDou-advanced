<?php

namespace common\models;

use Yii;

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
}
