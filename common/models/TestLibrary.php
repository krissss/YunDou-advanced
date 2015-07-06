<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "testlibrary".
 *
 * @property integer $testLibraryId
 * @property integer $testTypeId
 * @property integer $provenceId
 * @property string $problem
 * @property string $question
 * @property string $options
 * @property string $answer
 * @property string $analysis
 * @property string $picture
 * @property string $score
 * @property string $status
 * @property string $createDate
 * @property integer $createUserID
 * @property string $remark
 */
class TestLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testlibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testTypeId', 'provenceId', 'createUserID'], 'integer'],
            [['score'], 'number'],
            [['createDate'], 'safe'],
            [['problem', 'question', 'options'], 'string', 'max' => 400],
            [['answer'], 'string', 'max' => 10],
            [['analysis', 'picture'], 'string', 'max' => 200],
            [['status'], 'string', 'max' => 2],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'testLibraryId' => 'Test Library ID',
            'testTypeId' => 'Test Type ID',
            'provenceId' => 'Provence ID',
            'problem' => 'Problem',
            'question' => 'Question',
            'options' => 'Options',
            'answer' => 'Answer',
            'analysis' => 'Analysis',
            'picture' => 'Picture',
            'score' => 'Score',
            'status' => 'Status',
            'createDate' => 'Create Date',
            'createUserID' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }
}
