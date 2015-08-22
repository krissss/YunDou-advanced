<?php
/** 添加充值方案的表单 */
namespace backend\models\forms;

use common\models\Scheme;
use yii\base\Exception;
use yii\base\Model;

class AddRechargeForm extends Model
{
    public $schemeId;
    public $name;
    public $payMoney;
    public $getBitcoin;
    public $startDate;
    public $endDate;

    public function rules()
    {
        return [
            [['name','payMoney', 'getBitcoin','startDate','endDate'], 'required'],
            [['schemeId','payMoney', 'getBitcoin'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '方案名称',
            'payMoney' => '充值金额',
            'getBitcoin' => '获得云豆',
            'startDate' => '生效时间',
            'endDate' => '结束时间',
        ];
    }

    public function recordOne($state=false){
        if(!$this->schemeId){
            $scheme = new Scheme();
            $scheme->level = Scheme::LEVEL_ONE;
            $scheme->usageModeId = Scheme::USAGE_PAY;
        }else{
            $scheme = Scheme::findOne($this->schemeId);
        }
        $scheme->name = $this->name;
        $scheme->payMoney = $this->payMoney;
        $scheme->getBitcoin = $this->getBitcoin;
        $scheme->startDate = $this->startDate;
        $scheme->endDate = $this->endDate;
        if($state){
            $scheme->state = Scheme::STATE_ABLE;
        }else{
            $scheme->state = Scheme::STATE_DISABLE;
        }
        if(!$scheme->save()){
            throw new Exception("recharge-form scheme save error");
        }
    }

    public static function initWithId($id){
        $scheme = Scheme::findOne($id);
        $form = new AddRechargeForm();
        $form->schemeId = $id;
        $form->name = $scheme->name;
        $form->payMoney = $scheme->payMoney;
        $form->getBitcoin = $scheme->getBitcoin;
        $form->startDate = $scheme->startDate;
        $form->endDate = $scheme->endDate;
        return $form;
    }

}