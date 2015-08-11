<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "scheme".
 *
 * @property integer $schemeId
 * @property string $name
 * @property integer $payBitcoin
 * @property integer $day
 * @property integer $time
 * @property integer $payMoney
 * @property integer $getBitcoin
 * @property string $startDate
 * @property string $endDate
 * @property string $state
 * @property string $type
 * @property string $remark
 */
class Scheme extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'scheme';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payBitcoin', 'day', 'time', 'payMoney', 'getBitcoin'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['state','type'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'schemeId' => 'Scheme ID',
            'name' => 'Name',
            'payBitcoin' => 'Pay Bitcoin',
            'day' => 'Day',
            'time' => 'Time',
            'payMoney' => 'Pay Money',
            'getBitcoin' => 'Get Bitcoin',
            'startDate' => 'Start Date',
            'endDate' => 'End Date',
            'state' => 'State',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }
}
