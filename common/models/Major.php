<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "major".
 *
 * @property integer $majorId
 * @property string $majorName
 * @property string $description
 * @property string $remark
 */
class Major extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'major';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorName'], 'string', 'max' => 50],
            [['description', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'majorId' => 'Major ID',
            'majorName' => 'Major Name',
            'description' => 'Description',
            'remark' => 'Remark',
        ];
    }
}
