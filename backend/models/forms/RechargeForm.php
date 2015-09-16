<?php
/** 充值列表 */
namespace backend\models\forms;

use common\models\Money;
use common\models\Users;
use yii\base\Model;
use Yii;

class RechargeForm extends Model
{
    public $userId;
    public $from;
    public $money;
    public $bitcoin;
    public $agreement;

    public function rules()
    {
        return [
            [['userId','from','money','bitcoin','agreement'], 'required'],
            [['userId','from','bitcoin'], 'integer'],
            [['money'],'number'],
            [['agreement'], 'string', 'max' => 40],
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => '支付方式',
            'money' => '支付金额',
            'bitcoin' => '获得云豆',
            'agreement' => '协议编码',
        ];
    }

    public function record(){
        $userSession = Yii::$app->session->get('user');
        $user = Users::findOne($this->userId);
        Money::recordOneForBig($user,$this->money,$this->bitcoin,$this->from,$userSession['userId'],$this->agreement);
    }
}