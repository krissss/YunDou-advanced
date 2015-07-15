<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "examtemplatedetail".
 *
 * @property integer $examTemplateDetailId
 * @property integer $examTemplateId
 * @property integer $testTypeId
 * @property integer $preType
 * @property integer $testChapterId
 * @property integer $testNumber
 * @property string $remark
 */
class ExamTemplateDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examtemplatedetail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['examTemplateId', 'testTypeId', 'preType', 'testChapterId', 'testNumber'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examTemplateDetailId' => 'Exam Template Detail ID',
            'examTemplateId' => 'Exam Template ID',
            'testTypeId' => 'Test Type ID',
            'preType' => 'Pre Type',
            'testChapterId' => 'Test Chapter ID',
            'testNumber' => 'Test Number',
            'remark' => 'Remark',
        ];
    }
}
