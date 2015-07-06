<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "majorjob".
 *
 * @property integer $majorJobId
 * @property integer $majorId
 * @property integer $jobId
 * @property string $remark
 */
class MajorJob extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'majorjob';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorId', 'jobId'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'majorJobId' => 'Major Job ID',
            'majorId' => 'Major ID',
            'jobId' => 'Job ID',
            'remark' => 'Remark',
        ];
    }
}
