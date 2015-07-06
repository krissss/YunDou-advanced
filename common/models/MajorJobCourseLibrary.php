<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "majorjobcourselibrary".
 *
 * @property integer $majorJobCourseLibraryId
 * @property integer $majorJobRelationId
 * @property integer $courseLibraryId
 * @property string $remark
 */
class MajorJobCourseLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'majorjobcourselibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorJobRelationId', 'courseLibraryId'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'majorJobCourseLibraryId' => 'Major Job Course Library ID',
            'majorJobRelationId' => 'Major Job Relation ID',
            'courseLibraryId' => 'Course Library ID',
            'remark' => 'Remark',
        ];
    }
}
