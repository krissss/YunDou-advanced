<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "usagemode".
 *
 * @property integer $usageModeId
 * @property string $usageModeName
 * @property string $type
 * @property string $remark
 */
class UsageMode extends \yii\db\ActiveRecord
{
    const USAGE_PRACTICE = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usagemode';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['usageModeName'], 'string', 'max' => 50],
            [['type'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'usageModeId' => 'Usage Mode ID',
            'usageModeName' => 'Usage Mode Name',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }
}
