<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoiceId
 * @property integer $userId
 * @property string $description
 * @property string $address
 * @property integer $createUserID
 * @property string $createDate
 * @property string $remark
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'createUserID'], 'integer'],
            [['createDate'], 'safe'],
            [['description'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 60],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'invoiceId' => 'Invoice ID',
            'userId' => 'User ID',
            'description' => 'Description',
            'address' => 'Address',
            'createUserID' => 'Create User ID',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }
}
