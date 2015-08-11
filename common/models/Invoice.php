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
 * @property string $createDate
 * @property string $state
 * @property integer $replyUserId
 * @property string $replyContent
 * @property string $orderNumber
 * @property string $replyDate
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
            [['userId', 'replyUserId'], 'integer'],
            [['money'], 'number'],
            [['createDate', 'replyDate'], 'safe'],
            [['description', 'replyContent'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 60],
            [['state'], 'string', 'max' => 1],
            [['orderNumber'], 'string', 'max' => 30],
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
            'createDate' => 'Create Date',
            'state' => 'State',
            'replyUserId' => 'Reply User ID',
            'replyContent' => 'Reply Content',
            'orderNumber' => 'Order Number',
            'replyDate' => 'Reply Date',
            'remark' => 'Remark',
        ];
    }

    public function getUsers(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }
}
