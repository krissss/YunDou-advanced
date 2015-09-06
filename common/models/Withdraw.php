<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "withdraw".
 *
 * @property integer $withdrawId
 * @property integer $userId
 * @property string $money
 * @property string $description
 * @property string $invoiceMoney
 * @property string $invoiceNo
 * @property string $createDate
 * @property integer $replyUserId
 * @property string $replyDate
 * @property string $replyContent
 * @property integer $state
 * @property string $remark
 */
class Withdraw extends \yii\db\ActiveRecord
{
    const STATE_APPLYING = 0;
    const STATE_REFUSE = 1;
    const STATE_PASS = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'withdraw';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'replyUserId', 'state'], 'integer'],
            [['money', 'invoiceMoney'], 'number'],
            [['createDate', 'replyDate'], 'safe'],
            [['description', 'replyContent'], 'string', 'max' => 50],
            [['invoiceNo'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'withdrawId' => 'Withdraw ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'description' => 'Description',
            'invoiceMoney' => 'Invoice Money',
            'invoiceNo' => 'Invoice No',
            'createDate' => 'Create Date',
            'replyUserId' => 'Reply User ID',
            'replyDate' => 'Reply Date',
            'replyContent' => 'Reply Content',
            'state' => 'State',
            'remark' => 'Remark',
        ];
    }
}
