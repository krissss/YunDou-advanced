<?php

namespace common\models;

use common\functions\DateFunctions;
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
 * @property string $rebate //推荐人返点
 * @property string $rebateSelf //充值人返点
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
    const USAGE_PRACTICE = 2;   //在线练习支出
    const USAGE_PAY = 1;    //充值收入
    const USAGE_COURSE = 3; //在线课堂支出
    const USAGE_REBATE_A = 4; //A级用户充值返点收入
    const USAGE_SHARE = 5; //考试通过分享收入
    const USAGE_SHOP = 6; //商城购物支出
    const USAGE_WITHDRAW = 7; //提现支出
    const USAGE_REBATE_AA = 8; //AA级用户充值返点收入
    const USAGE_REBATE_AAA = 9; //AAA级用户充值返点收入

    const LEVEL_UNDO = 0;   //不能动
    const LEVEL_ONE = 1;

    public static function tableName()
    {
        return 'scheme';
    }

    public function rules()
    {
        return [
            [['payBitcoin', 'day', 'time', 'payMoney', 'getBitcoin', 'usageModeId', 'level'], 'integer'],
            [['rebate','rebateSelf'], 'number'],
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
            'rebate' => 'Rebate',
            'rebateSelf' => 'Rebate Self',
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
            ->orderBy(['startDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询所有在线练习支付方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findAllPracticeScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PRACTICE])
            ->orderBy(['startDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询所有充值返点方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findAllRebateScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_REBATE_A])
            ->orWhere(['usageModeId'=>Scheme::USAGE_REBATE_AA])
            ->orWhere(['usageModeId'=>Scheme::USAGE_REBATE_AAA])
            ->orderBy(['startDate'=>SORT_DESC])
            ->all();
    }

    /**
     * 查询一条应该使用的充值方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findPayScheme(){
        $currentDate = DateFunctions::getCurrentDate();
        $scheme = Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PAY,'state'=>Scheme::STATE_ABLE])
            ->andWhere(['>','level',Scheme::LEVEL_UNDO])
            ->andWhere(['<=','startDate',$currentDate])
            ->andWhere(['>=','endDate',$currentDate])
            ->one();
        if(!$scheme){
            $scheme = Scheme::find()
                ->where(['usageModeId'=>Scheme::USAGE_PAY,'state'=>Scheme::STATE_ABLE,'level'=>Scheme::LEVEL_UNDO])
                ->one();
        }
        return $scheme;
    }

    /**
     * 查询一条应该使用的在线练习支付方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findPracticeScheme(){
        $currentDate = DateFunctions::getCurrentDate();
        $scheme = Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_PRACTICE,'state'=>Scheme::STATE_ABLE])
            ->andWhere(['>','level',Scheme::LEVEL_UNDO])
            ->andWhere(['<=','startDate',$currentDate])
            ->andWhere(['>=','endDate',$currentDate])
            ->one();
        if(!$scheme){
            $scheme = Scheme::find()
                ->where(['usageModeId'=>Scheme::USAGE_PRACTICE,'state'=>Scheme::STATE_ABLE,'level'=>Scheme::LEVEL_UNDO])
                ->one();
        }
        return $scheme;
    }

    /**
     * 查询一条应该使用的充值返点方案
     * @param $role //推荐人的用户等级
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findRebateScheme($role){
        switch($role){
            case Users::ROLE_A:
                $usageModeId = Scheme::USAGE_REBATE_A;
                break;
            case Users::ROLE_AA:
                $usageModeId = Scheme::USAGE_REBATE_AA;
                break;
            case Users::ROLE_AAA:
                $usageModeId = Scheme::USAGE_REBATE_AAA;
                break;
            default:
                return null;
                break;
        }
        $currentDate = DateFunctions::getCurrentDate();
        $scheme = Scheme::find()
            ->where(['usageModeId'=>$usageModeId,'state'=>Scheme::STATE_ABLE])
            ->andWhere(['>','level',Scheme::LEVEL_UNDO])
            ->andWhere(['<=','startDate',$currentDate])
            ->andWhere(['>=','endDate',$currentDate])
            ->one();
        if(!$scheme){
            $scheme = Scheme::find()
                ->where(['usageModeId'=>$usageModeId,'state'=>Scheme::STATE_ABLE,'level'=>Scheme::LEVEL_UNDO])
                ->one();
        }
        return $scheme;
    }

    /**
     * 检查方案是否有启用冲突
     * @param $usageModeId
     * @param $startDate
     * @param $endDate
     * @return bool|mixed
     */
    public static function checkScheme($usageModeId,$startDate,$endDate){
        $scheme = $scheme = Scheme::find()
            ->where(['usageModeId'=>$usageModeId,'state'=>Scheme::STATE_ABLE])
            ->andWhere(['>','level',Scheme::LEVEL_UNDO])
            ->andWhere(['<=','startDate',$startDate])
            ->andWhere(['>=','endDate',$startDate])
            ->one();
        if(!$scheme){   //如果开始时间不在启用的时间当中
            $scheme = $scheme = Scheme::find()
                ->where(['usageModeId'=>$usageModeId,'state'=>Scheme::STATE_ABLE])
                ->andWhere(['>','level',Scheme::LEVEL_UNDO])
                ->andWhere(['<=','startDate',$endDate])
                ->andWhere(['>=','endDate',$endDate])
                ->one();
        }
        if(!$scheme){   //如果结束时间不在启用的时间当中
            return false;   //表示没有冲突
        }
        return $scheme->name; //返回冲突的方案的名称
    }

    /**
     * 更新状态
     * @param $schemeId
     * @param $newState
     * @return bool
     * @throws Exception
     * @throws \Exception
     */
    public static function updateState($schemeId,$newState){
        $scheme = Scheme::findOne($schemeId);
        if(!$scheme){
            throw new Exception("Scheme id not exist");
        }
        if($newState == Scheme::STATE_ABLE){
            $checkResult = Scheme::checkScheme($scheme->usageModeId,$scheme->startDate,$scheme->endDate);   //更新开启状态前检查冲突
            if($checkResult){
                return "方案启用失败，启用的方案中存在与想要设置的方案时间存在冲突，冲突方案名称是：".$checkResult;
            }
        }
        $scheme->state = $newState;
        if(!$scheme->update()){
            throw new Exception("ExamTemplate update error");
        }
        return false;
    }
}
