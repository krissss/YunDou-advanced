<?php
/** 添加或更新在线练习方案的表单 */
namespace backend\models\forms;

use common\functions\CommonFunctions;
use common\models\Scheme;
use yii\base\Exception;
use yii\base\Model;

class AddRebateForm extends Model
{
    public $schemeId;
    public $name;
    public $payMoney;
    public $rebate;
    public $rebateSelf;
    public $startDate;
    public $endDate;
    public $usageModeId;

    public function rules()
    {
        return [
            [['name','usageModeId','payMoney', 'rebate','rebateSelf','startDate','endDate'], 'required'],
            [['schemeId','payMoney','usageModeId'], 'integer'],
            [['rebate'], 'number'],
            [['startDate', 'endDate'], 'safe'],
            [['endDate'], 'compare','compareAttribute' => 'startDate','operator' => '>'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '方案名称',
            'payMoney' => '起始消费',
            'rebate' => '推荐人返点',
            'rebateSelf' => '充值人返点',
            'startDate' => '生效时间',
            'endDate' => '结束时间',
            'usageModeId' => '方案等级',
        ];
    }

    public function recordOne($state=false){
        if(!$this->schemeId){
            $scheme = new Scheme();
            $scheme->level = Scheme::LEVEL_ONE;
        }else{
            $scheme = Scheme::findOne($this->schemeId);
        }
        $scheme->usageModeId = $this->usageModeId;
        $scheme->name = $this->name;
        $scheme->payMoney = $this->payMoney;
        $scheme->rebate = $this->rebate;
        $scheme->rebateSelf = $this->rebateSelf;
        $scheme->startDate = $this->startDate;
        $scheme->endDate = $this->endDate;
        if($state){
            $checkResult = Scheme::checkScheme($scheme->usageModeId,$this->startDate,$this->endDate);  //检查方案冲突
            if($checkResult){
                CommonFunctions::createAlertMessage("方案设置失败，启用的方案中存在与想要设置的方案时间存在冲突，冲突方案名称是：".$checkResult,"error");
                return true;
            }
            $scheme->state = Scheme::STATE_ABLE;
        }else{
            $scheme->state = Scheme::STATE_DISABLE;
        }
        if(!$scheme->save()){
            throw new Exception("practice-form scheme save error");
        }
        return false;
    }

    public static function initWithId($id){
        $scheme = Scheme::findOne($id);
        $form = new AddRebateForm();
        $form->schemeId = $id;
        $form->name = $scheme->name;
        $form->payMoney = $scheme->payMoney;
        $form->rebate = $scheme->rebate;
        $form->startDate = $scheme->startDate;
        $form->endDate = $scheme->endDate;
        return $form;
    }

}