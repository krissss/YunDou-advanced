<?php
/** 充值列表 */
namespace backend\models\forms;

use common\functions\DateFunctions;
use common\models\IncomeConsume;
use common\models\Money;
use common\models\Scheme;
use yii\base\Exception;
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
        $user = Yii::$app->session->get('user');
        $money = new Money();
        $money->userId = $this->userId;
        $money->money = $this->money;
        $money->bitcoin = $this->bitcoin;
        $money->agreement = $this->agreement;
        $money->from = $this->from;
        $money->type = Money::TYPE_PAY;
        $money->createDate = DateFunctions::getCurrentDate();
        $money->operateUserId = $user['userId'];
        if(!$money->save()){
            throw new Exception("recharge from money save error");
        }
        IncomeConsume::saveRecord($this->userId,$this->bitcoin,Scheme::USAGE_PAY,IncomeConsume::TYPE_INCOME,$user['userId']);
    }
}