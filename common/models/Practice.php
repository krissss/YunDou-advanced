<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "practice".
 *
 * @property integer $practiceId
 * @property integer $userId
 * @property integer $testLibraryId
 * @property string $userAnswer
 * @property string $createDate
 * @property string $remark
 */
class Practice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'practice';
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
            'practiceId' => 'Practice ID',
            'userId' => 'User ID',
            'testLibraryId' => 'Test Library ID',
            'userAnswer' => 'User Answer',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }
}
