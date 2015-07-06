<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "job".
 *
 * @property integer $jobId
 * @property string $jobName
 * @property string $description
 * @property string $remark
 */
class Job extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'job';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['jobName'], 'string', 'max' => 50],
            [['description', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'jobId' => 'Job ID',
            'jobName' => 'Job Name',
            'description' => 'Description',
            'remark' => 'Remark',
        ];
    }
}
