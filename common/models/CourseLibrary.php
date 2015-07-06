<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "courselibrary".
 *
 * @property integer $courseLibraryId
 * @property string $courseName
 * @property string $tips
 * @property string $path
 * @property string $pic
 * @property string $createDate
 * @property integer $createUserID
 * @property string $remark
 */
class CourseLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'courselibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['createDate'], 'safe'],
            [['createUserID'], 'integer'],
            [['courseName', 'remark'], 'string', 'max' => 100],
            [['tips', 'path', 'pic'], 'string', 'max' => 200]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'courseLibraryId' => 'Course Library ID',
            'courseName' => 'Course Name',
            'tips' => 'Tips',
            'path' => 'Path',
            'pic' => 'Pic',
            'createDate' => 'Create Date',
            'createUserID' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }
}
