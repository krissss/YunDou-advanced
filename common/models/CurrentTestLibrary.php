<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "currenttestlibrary".
 *
 * @property integer $currentTestLibraryId
 * @property integer $userID
 * @property integer $testlibraryID
 * @property integer $testTypeID
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
            [['userID', 'testlibraryID', 'testTypeID'], 'integer'],
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
            'userID' => 'User ID',
            'testlibraryID' => 'Testlibrary ID',
            'testTypeID' => 'Test Type ID',
            'remark' => 'Remark',
        ];
    }
}
