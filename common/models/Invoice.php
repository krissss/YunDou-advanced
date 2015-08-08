<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoice".
 *
 * @property integer $invoiceId
 * @property integer $userId
 * @property string $money
 * @property string $description
 * @property string $address
 * @property integer $createUserId
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
            [['userId', 'createUserId'], 'integer'],
            [['money'], 'number'],
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
            'money' => 'Money',
            'description' => 'Description',
            'address' => 'Address',
            'createUserId' => 'Create User ID',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

}
