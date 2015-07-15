<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "majorjob".
 *
 * @property integer $majorJobId
 * @property string $name
 * @property string $code
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
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 1],
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
            'name' => 'Name',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }
}
