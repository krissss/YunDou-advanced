<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "examtemplate".
 *
 * @property integer $examTemplateId
 * @property integer $provenceId
 * @property integer $marjorJobId
 * @property string $name
 * @property string $createDate
 * @property integer $createUserId
 * @property string $remark
 */
class ExamTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'examtemplate';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provenceId', 'marjorJobId', 'createUserId'], 'integer'],
            [['createDate'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'examTemplateId' => 'Exam Template ID',
            'provenceId' => 'Provence ID',
            'marjorJobId' => 'Marjor Job ID',
            'name' => 'Name',
            'createDate' => 'Create Date',
            'createUserId' => 'Create User ID',
            'remark' => 'Remark',
        ];
    }
}
