<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "userrecord".
 *
 * @property integer $userRecordId
 * @property integer $userId
 * @property integer $recordType
 * @property string $recordDate
 * @property string $remark
 */
class UserRecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'userrecord';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'recordType'], 'integer'],
            [['recordDate'], 'safe'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userRecordId' => 'User Record ID',
            'userId' => 'User ID',
            'recordType' => 'Record Type',
            'recordDate' => 'Record Date',
            'remark' => 'Remark',
        ];
    }
}
