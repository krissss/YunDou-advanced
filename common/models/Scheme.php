<?php

namespace common\models;

use Yii;
use yii\base\Exception;

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
 * @property integer $usageModeId
 * @property integer $level
 * @property string $remark
 */
class Scheme extends \yii\db\ActiveRecord
{
    const STATE_ABLE = 'T';
    const STATE_DISABLE = 'F';

    //务必保持和usageMode表一致
    const USAGE_PRACTICE = 2;
    const USAGE_PAY = 1;
    const USAGE_COURSE = 3;

    const LEVEL_UNDO = 0;   //不能动
    const LEVEL_ONE = 1;

    public static function tableName()
    {
        return 'scheme';
    }

    public function rules()
    {
        return [
            [['payBitcoin', 'day', 'time', 'payMoney', 'getBitcoin','usageModeId','level'], 'integer'],
            [['startDate', 'endDate'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['state'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

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
            'usageModeId' => 'Usage Mode ID',
            'level' => 'Level',
            'remark' => 'Remark',
        ];
    }

    public function getStateName(){
        if($this->state == Scheme::STATE_ABLE){
            $content = "已启用";
        }elseif($this->state == Scheme::STATE_DISABLE){
            $content = "未启用";
        }else{
            $content="状态未定义";
        }
        return $content;
    }

    /**
 * 查询所有充值方案
 * @return array|null|\yii\db\ActiveRecord
 */
    public static function findAllPayScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PAY])
            ->all();
    }

    /**
     * 查询一条应该使用的充值方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findPayScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PAY,'state'=>Scheme::STATE_ABLE])
            ->one();
    }

    /**
     * 查询所有在线练习支付方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findAllPracticeScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PRACTICE])
            ->all();
    }

    /**
     * 查询一条应该使用的在线练习支付方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findPracticeScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PRACTICE,'state'=>Scheme::STATE_ABLE])
            ->one();
    }

    /**
     * 更新状态
     * @param $schemeId
     * @param $newState
     * @throws Exception
     * @throws \Exception
     */
    public static function updateState($schemeId,$newState){
        $scheme = Scheme::findOne($schemeId);
        if(!$scheme){
            throw new Exception("Scheme id not exist");
        }
        $scheme->state = $newState;
        if(!$scheme->update()){
            throw new Exception("ExamTemplate update error");
        }
    }
}
