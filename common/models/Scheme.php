<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;
use yii\db\Query;

/**
 * This is the model class for table "scheme".
 *
 * @property integer $schemeId
 * @property string $name
 * @property integer $payBitcoin
 * @property integer $hour
 * @property integer $time
 * @property integer $payMoney
 * @property string $getMoney
 * @property integer $getBitcoin
 * @property integer $totalBitcoin
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
    const USAGE_DISTRIBUTE_INCOME = 10; //大客户分配收入
    const USAGE_DISTRIBUTE_CONSUME = 11; //大客户分配支出
    const USAGE_REBATE_BIG = 12; //大客户充值返点收入
    const USAGE_WITHDRAW_AA = 13; //金牌伙伴提现支出
    const USAGE_WITHDRAW_AAA_LOW = 14; //钻石伙伴提现支出-低比例
    const USAGE_WITHDRAW_AAA_HIGH = 15; //钻石伙伴提现支出-高比例

    const LEVEL_UNDO = 0;   //不能动
    const LEVEL_ONE = 1;

    public static function tableName()
    {
        return 'scheme';
    }

    public function rules()
    {
        return [
            [['payBitcoin', 'hour', 'time', 'payMoney', 'getBitcoin', 'totalBitcoin', 'usageModeId', 'level'], 'integer'],
            [['getMoney', 'rebate', 'rebateSelf'], 'number'],
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
            'hour' => 'Hour',
            'time' => 'Time',
            'payMoney' => 'Pay Money',
            'getMoney' => 'Get Money',
            'getBitcoin' => 'Get Bitcoin',
            'totalBitcoin' => 'Total Bitcoin',
            'rebate' => 'Rebate',
            'rebateSelf' => 'Rebate Self',
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

    public function getUsageModelName(){
        if($this->usageModeId == Scheme::USAGE_REBATE_A){
            $content = "A级";
        }elseif($this->usageModeId == Scheme::USAGE_REBATE_AA){
            $content = "金牌伙伴";
        }elseif($this->usageModeId == Scheme::USAGE_REBATE_AAA){
            $content = "钻石伙伴";
        }elseif($this->usageModeId == Scheme::USAGE_REBATE_BIG){
            $content = "大客户";
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
     * 查询所有提现方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findAllWithdrawScheme(){
        return Scheme::find()
            ->where(['usageModeId'=>Scheme::USAGE_WITHDRAW_AA])
            ->orWhere(['usageModeId'=>Scheme::USAGE_WITHDRAW_AAA_LOW])
            ->orWhere(['usageModeId'=>Scheme::USAGE_WITHDRAW_AAA_HIGH])
            ->all();
    }

    /**
     * 查询所有可使用的充值方案
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findPayScheme(){
        $currentDate = DateFunctions::getCurrentDate();
        $scheme = (new Query())
            ->from(Scheme::tableName())
            ->where('usageModeId=:usageModeId and state=:state and (startDate is null or (startDate<=:currentDate and endDate>=:currentDate))',[':usageModeId' => Scheme::USAGE_PAY,':state'=>Scheme::STATE_ABLE,':currentDate'=>$currentDate])
            ->all();
        return $scheme;
    }

    /**
     * 查询所有可使用的线练习支付方案
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findPracticeScheme(){
        $currentDate = DateFunctions::getCurrentDate();
        $scheme = (new Query())
            ->from(Scheme::tableName())
            ->where('usageModeId=:usageModeId and state=:state and (startDate is null or (startDate<=:currentDate and endDate>=:currentDate))',[':usageModeId' => Scheme::USAGE_PRACTICE,':state'=>Scheme::STATE_ABLE,':currentDate'=>$currentDate])
            ->all();
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
            case Users::ROLE_BIG:
                $usageModeId = Scheme::USAGE_REBATE_BIG;
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
     * 根据role查询可使用的提现方案
     * @param $role
     * @return array|\yii\db\ActiveRecord[]
     * @throws Exception
     */
    public static function findWithdrawScheme($role){
        if($role == Users::ROLE_AA){
            $schemes = Scheme::find()
                ->where(['usageModeId'=>UsageMode::USAGE_WITHDRAW_AA,'state'=>Scheme::STATE_ABLE])
                ->all();
        }elseif($role == Users::ROLE_AAA){
            $schemes = Scheme::find()
                ->where(['usageModeId'=>UsageMode::USAGE_WITHDRAW_AAA_LOW,'state'=>Scheme::STATE_ABLE])
                ->orWhere(['usageModeId'=>UsageMode::USAGE_WITHDRAW_AAA_HIGH,'state'=>Scheme::STATE_ABLE])
                ->orderBy(['usageModeId'=>SORT_ASC])    //返回的数组一定是第一个为第比例方案，第二个为高比例方案
                ->all();
        }else{
            throw new Exception("role 未定义");
        }
        return $schemes;
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

    /**
     * 计算用户最大可提现的金额
     * @param $user
     * @return float
     * @throws Exception
     */
    public static function calculateWithdrawMaxMoney($user){
        $role = $user['role'];
        $leftBitcoin = Users::findBitcoin($user['userId']); //获取用户剩余云豆

        $schemes = Scheme::findWithdrawScheme($role);
        if($role == Users::ROLE_AA){
            $scheme = $schemes[0];
            return $leftBitcoin/$scheme['payBitcoin']*$scheme['getMoney'];
        }elseif($role == Users::ROLE_AAA){
            $totalBitcoin = IncomeConsume::findTotalIncome($user['userId']);    //获取该用户累计获得多少云豆
            $withdrawBitcoin = $totalBitcoin-$leftBitcoin;  //已经提现掉的云豆数
            $scheme_low = $schemes[0];
            $scheme_high = $schemes[1];
            if($withdrawBitcoin >= $scheme_high['totalBitcoin']){  //已经提现的云豆数大于高比例需要的云豆数，直接按照高比例算
                return $leftBitcoin/$scheme_high['payBitcoin']*$scheme_high['getMoney'];
            }else{  //按照第比例算或分段算
                if($totalBitcoin >= $scheme_high['totalBitcoin']){  //累计云豆数量达到高比例提现，分段算
                    $lowBitcoin = $scheme_high['totalBitcoin']-$withdrawBitcoin;    //按照低比例算的云豆
                    $highBitcoin = $leftBitcoin-$lowBitcoin;    //按照高比例算的云豆
                    return $lowBitcoin/$scheme_low['payBitcoin']*$scheme_low['getMoney']+$highBitcoin/$scheme_high['payBitcoin']*$scheme_high['getMoney'];
                }else{  //按第比例算
                    return $leftBitcoin/$scheme_low['payBitcoin']*$scheme_low['getMoney'];
                }
            }
        }else{
            throw new Exception("role 未定义");
        }
    }

    /**
     * 计算用户提现的钱应该扣除的云豆数
     * @param $user
     * @param $money
     * @return float|mixed
     * @throws Exception
     */
    public static function calculateWithdrawBitcoin($user,$money){
        $role = $user['role'];
        $leftBitcoin = Users::findBitcoin($user['userId']); //获取用户剩余云豆

        $schemes = Scheme::findWithdrawScheme($role);
        if($role == Users::ROLE_AA){
            $scheme = $schemes[0];
            return $money/$scheme['getMoney']*$scheme['payBitcoin'];
        }elseif($role == Users::ROLE_AAA){
            $totalBitcoin = IncomeConsume::findTotalIncome($user['userId']);    //获取该用户累计获得多少云豆
            $withdrawBitcoin = $totalBitcoin-$leftBitcoin;  //已经提现掉的云豆数
            $scheme_low = $schemes[0];
            $scheme_high = $schemes[1];
            if($withdrawBitcoin >= $scheme_high['totalBitcoin']){  //已经提现的云豆数大于高比例需要的云豆数，直接按照高比例算
                return $money/$scheme_high['getMoney']*$scheme_high['payBitcoin'];
            }else{  //按照第比例算或分段算
                if($totalBitcoin >= $scheme_high['totalBitcoin']){  //累计云豆数量达到高比例提现，分段算
                    $lowBitcoin = $scheme_high['totalBitcoin']-$withdrawBitcoin;    //按照低比例算的云豆
                    $lowMoney = $lowBitcoin/$scheme_low['payBitcoin']*$scheme_low['getMoney'];  //低比例最大可提现的钱
                    if($money<=$lowMoney){  //用户体现的钱未达到低比例最大提现的钱，按低比例计算云豆
                        return $money/$scheme_low['getMoney']*$scheme_low['payBitcoin'];
                    }else{  //用户体现的钱超过低比例最大提现的钱，未超过部分为所有低比例云豆数，超过部分按高比例计算云豆
                        return $lowBitcoin+($money-$lowMoney)/$scheme_high['getMoney']*$scheme_high['payBitcoin'];
                    }
                }else{  //按低比例算
                    return $money/$scheme_low['getMoney']*$scheme_low['payBitcoin'];
                }
            }
        }else{
            throw new Exception("role 未定义");
        }
    }
}
