<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property integer $payId
 * @property integer $userId
 * @property string $money
 * @property integer $invoiceId
 * @property string $createDate
 * @property string $remark
 */
class Pay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'invoiceId'], 'integer'],
            [['money'], 'number'],
            [['createDate'], 'safe'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'payId' => 'Pay ID',
            'userId' => 'User ID',
            'money' => 'Money',
            'invoiceId' => 'Invoice ID',
            'createDate' => 'Create Date',
            'remark' => 'Remark',
        ];
    }
}
