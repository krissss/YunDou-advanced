<?php
/** 添加或更新在线练习方案的表单 */
namespace backend\models\forms;

use common\models\Scheme;
use yii\base\Exception;
use yii\base\Model;

class AddPracticeForm extends Model
{
    public $schemeId;
    public $name;
    public $payBitcoin;
    public $day;
    public $startDate;
    public $endDate;

    public function rules()
    {
        return [
            [['name','payBitcoin', 'day','startDate','endDate'], 'required'],
            [['schemeId','payBitcoin', 'day'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['endDate'], 'compare','compareAttribute' => 'startDate','operator' => '>'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => '方案名称',
            'payBitcoin' => '花费云豆',
            'day' => '使用天数',
            'startDate' => '生效时间',
            'endDate' => '结束时间',
        ];
    }

    public function recordOne($state=false){
        if(!$this->schemeId){
            $scheme = new Scheme();
            $scheme->level = Scheme::LEVEL_ONE;
            $scheme->usageModeId = Scheme::USAGE_PRACTICE;
        }else{
            $scheme = Scheme::findOne($this->schemeId);
        }
        $scheme->name = $this->name;
        $scheme->payBitcoin = $this->payBitcoin;
        $scheme->day = $this->day;
        $scheme->startDate = $this->startDate;
        $scheme->endDate = $this->endDate;
        if($state){
            $scheme->state = Scheme::STATE_ABLE;
        }else{
            $scheme->state = Scheme::STATE_DISABLE;
        }
        if(!$scheme->save()){
            throw new Exception("practice-form scheme save error");
        }
    }

    public static function initWithId($id){
        $scheme = Scheme::findOne($id);
        $form = new AddPracticeForm();
        $form->schemeId = $id;
        $form->name = $scheme->name;
        $form->payBitcoin = $scheme->payBitcoin;
        $form->day = $scheme->day;
        $form->startDate = $scheme->startDate;
        $form->endDate = $scheme->endDate;
        return $form;
    }

}