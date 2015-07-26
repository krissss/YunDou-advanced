<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "testlibrary".
 *
 * @property integer $testLibraryId
 * @property integer $provinceId
 * @property integer $testTypeId
 * @property integer $majorJobId
 * @property integer $preTypeId
 * @property integer $testChapterId
 * @property string $problem
 * @property string $question
 * @property string $options
 * @property string $answer
 * @property string $analysis
 * @property string $picture
 * @property string $score
 * @property string $status
 * @property string $createDate
 * @property integer $createUserId
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
            [['provinceId', 'testTypeId', 'majorJobId', 'preTypeId', 'testChapterId', 'createUserId'], 'integer'],
            [['createDate'], 'safe'],
            [['problem', 'question', 'options'], 'string', 'max' => 400],
            [['answer'], 'string', 'max' => 10],
            [['analysis', 'picture'], 'string', 'max' => 200],
            [['score'], 'string', 'max' => 20],
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
            'provinceId' => 'Province ID',
            'testTypeId' => 'Test Type ID',
            'majorJobId' => 'Major Job ID',
            'preTypeId' => 'Pre Type ID',
            'testChapterId' => 'Test Chapter ID',
            'problem' => 'Problem',
            'question' => 'Question',
            'options' => 'Options',
            'answer' => 'Answer',
            'analysis' => 'Analysis',
            'picture' => 'Picture',
            'score' => 'Score',
            'status' => 'Status',
            'createDate' => 'Create Date',
            'createUserId' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }

    public static function findOneByType($provinceId,$majorJobId,$testTypeId,$startTestLibraryId = 0){
        return TestLibrary::find()
            ->where(['provinceId'=>$provinceId,'majorJobId'=>$majorJobId,'testTypeId'=>$testTypeId])
            ->andWhere(['>=','testLibraryId',$startTestLibraryId])
            ->asArray()
            ->all();
    }
}
