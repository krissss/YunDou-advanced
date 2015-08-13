<?php

namespace frontend\models\forms;

use yii\base\Model;

class RechargeForm extends Model
{
    public $money;

    public function rules()
    {
        return [
            [['money'], 'required'],
            [['money'], 'integer','min'=>1,'max'=>100000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '充值金额',
        ];
    }
}