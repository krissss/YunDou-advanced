<?php

namespace common\models;

use Yii;

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
}
