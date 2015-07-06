<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "testtype".
 *
 * @property integer $testTypeId
 * @property string $testTypeName
 * @property string $remark
 */
class TestType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['testTypeName'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'testTypeId' => 'Test Type ID',
            'testTypeName' => 'Test Type Name',
            'remark' => 'Remark',
        ];
    }
}
