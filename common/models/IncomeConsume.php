<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "incomeconsume".
 *
 * @property integer $incomeConsumeId
 * @property integer $userId
 * @property integer $bitcoin
 * @property integer $usageModeId
 * @property string $createDate
 * @property string $type
 * @property string $remark
 */
class IncomeConsume extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'incomeconsume';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'bitcoin', 'usageModeId'], 'integer'],
            [['createDate'], 'safe'],
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
            'incomeConsumeId' => 'Income Consume ID',
            'userId' => 'User ID',
            'bitcoin' => 'Bitcoin',
            'usageModeId' => 'Usage Mode ID',
            'createDate' => 'Create Date',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public function getUsageMode(){
        return $this->hasOne(UsageMode::className(),['usageModeId'=>'usageModeId']);
    }
}
