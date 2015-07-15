<?php

namespace common\models;

use Yii;

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
}
