<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bankcard".
 *
 * @property integer $bankCardId
 * @property integer $userId
 * @property string $bankName
 * @property string $cardNumber
 * @property string $cardName
 * @property integer $state
 * @property string $remark
 */
class BankCard extends \yii\db\ActiveRecord
{
    const STATE_DEFAULT = 0;    //默认状态

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bankcard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'state'], 'integer'],
            [['bankName'], 'string', 'max' => 50],
            [['cardNumber'], 'string', 'max' => 25],
            [['cardName'], 'string', 'max' => 20],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'bankCardId' => 'Bank Card ID',
            'userId' => 'User ID',
            'bankName' => 'Bank Name',
            'cardNumber' => 'Card Number',
            'cardName' => 'Card Name',
            'state' => 'State',
            'remark' => 'Remark',
        ];
    }
}
