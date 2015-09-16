<?php

namespace common\models;

use frontend\functions\WeiXinFunctions;
use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $userId
 * @property string $username
 * @property string $password
 * @property string $userIcon
 * @property string $email
 * @property string $cellphone
 * @property string $weixin
 * @property string $qq
 * @property string $sex
 * @property integer $majorJobId
 * @property string $nickname
 * @property string $realname
 * @property string $introduce
 * @property integer $bitcoin
 * @property integer $provinceId
 * @property integer $cityId
 * @property string $company
 * @property string $address
 * @property string $registerDate
 * @property integer $role
 * @property string $recommendCode
 * @property integer $recommendUserID
 * @property integer $departmentId
 * @property string $state
 * @property string $remark
 */
class Users extends \yii\db\ActiveRecord
{
    const ROLE_A = 1;   //A级
    const ROLE_AA = 2;  //AA级
    const ROLE_AAA = 3; //AAA级
    const ROLE_BIG = 4; //大客户
    const ROLE_ADMIN = 10;  //管理员
    const ROLE_MANAGER = 30;    //总经理
    const ROLE_OPERATION = 25;  //运维
    const ROLE_SALE = 20;   //销售
    const ROLE_DEVELOP = 15;    //开发

    const STATE_NORMAL = 'N';   //正常状态，所有人基本状态
    const STATE_FROZEN = 'U';   //冻结状态，伙伴和大客户
    const STATE_STOP = 'D';   //终止状态，伙伴和大客户
    const STATE_REMOVE = 'R';   //非员工状态，大客户取消确认员工后的状态


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorJobId', 'bitcoin', 'provinceId', 'cityId', 'role', 'recommendUserID','departmentId'], 'integer'],
            [['registerDate'], 'safe'],
            [['username', 'password', 'email', 'weixin', 'nickname', 'realname', 'company', 'address'], 'string', 'max' => 50],
            [['userIcon'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 2],
            [['state'], 'string', 'max' => 1],
            [['cellphone'], 'string', 'max' => 11],
            [['qq'], 'string', 'max' => 12],
            [['recommendCode'], 'string', 'max' => 15],
            [['introduce', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => '用户id',
            'username' => '用户名',
            'password' => '密码',
            'userIcon' => '用户头像',
            'email' => '邮件',
            'cellphone' => '手机',
            'weixin' => '微信id',
            'qq' => 'qq',
            'sex' => '性别',
            'majorJobId' => '专业岗位',
            'nickname' => '昵称',
            'realname' => '真名',
            'introduce' => '简介',
            'bitcoin' => '云豆',
            'provinceId' => '省份',
            'cityId' => '城市',
            'company' => '工作单位',
            'address' => '家庭住址',
            'registerDate' => '注册日期',
            'role' => '角色等级',
            'recommendCode' => '推荐码',
            'recommendUserID' => '推荐用户',
            'departmentId' => '部门号',
            'state' => '用户状态',
            'remark' => 'Remark',
        ];
    }

    public function getProvince(){
        return $this->hasOne(Province::className(),['provinceId'=>'provinceId']);
    }

    public function getMajorJob(){
        return $this->hasOne(MajorJob::className(),['majorJobId'=>'majorJobId']);
    }

    public function getBankCard(){
        return $this->hasOne(BankCard::className(),['userId'=>'userId']);
    }

    public function getRoleName(){
        switch($this->role){
            case Users::ROLE_A: $msg = "A级"; break;
            case Users::ROLE_AA: $msg = "金牌伙伴"; break;
            case Users::ROLE_AAA: $msg = "钻石伙伴"; break;
            case Users::ROLE_BIG: $msg = "大客户"; break;
            case Users::ROLE_ADMIN: $msg = "管理员"; break;
            case Users::ROLE_DEVELOP: $msg = "开发人员"; break;
            case Users::ROLE_SALE: $msg = "销售人员"; break;
            case Users::ROLE_OPERATION: $msg = "运维人员"; break;
            case Users::ROLE_MANAGER: $msg = "总经理"; break;
            default: $msg = "未定义";
        }
        return $msg;
    }

    public function getStateName(){
        if($this->state == Users::STATE_NORMAL){
            return "正常";
        }elseif($this->state == Users::STATE_FROZEN){
            return "冻结";
        }elseif($this->state == Users::STATE_STOP){
            return "终止";
        }elseif($this->state == Users::STATE_REMOVE){
            return "已去除";
        }else{
            return "未知";
        }
    }

    public function getRecommendUser(){
        return $this->hasOne(Users::className(),['userId'=>'recommendUserID']);
    }

    public function getDepartment(){
        return $this->hasOne(Department::className(),['departmentId'=>'departmentId']);
    }

    /**
     * 微信用户关注将用户信息导入数据库
     * @param $openId
     */
    public static function wxSubscribe($openId){
        $user = Users::find()
            ->where(['weixin'=>$openId])
            ->one();
        if(!$user){
            $user = new Users();
            $user->weixin = $openId;
            $userInfo = WeiXinFunctions::getUserInfo($openId);
            $user->role = Users::ROLE_A;
            $user->weixin = $userInfo->openid;
            $user->nickname = $userInfo->nickname;
            $user->userIcon = $userInfo->headimgurl;
            $user->state = Users::STATE_NORMAL;
            if($userInfo->sex == 1){
                $user->sex = '男';
            }elseif($userInfo->sex == 2){
                $user->sex = '女';
            }else{}
            $user->save();
        }
    }

    /**
     * 通过微信的openId查询用户
     * @param $openId
     * @return array|null|\yii\db\ActiveRecord
     */
    public static function findByWeiXin($openId){
        return Users::find()
            ->where(['weixin'=>$openId])
            ->one();
    }

    /**
     * 根据openId获取用户session
     * @param $openId
     * @return mixed
     */
    public static function getSessionUser($openId){
        $session = Yii::$app->session;
        //$session->remove('user');
        $user = $session->get('user');
        if(!$user){
            $session->set('user',self::findByWeiXin($openId));
            $user = $session->get('user');
        }
        return $user;
    }

    /**
     * 获取用户的云豆数
     * @param $userId
     * @return mixed
     */
    public static function findBitcoin($userId){
        $user = Users::find()
            ->select('bitcoin')
            ->where(["userId"=>$userId])
            ->asArray()
            ->one();
        return $user['bitcoin'];
    }

    /**
     * 根据提现编号查找提现用户的云豆余额
     * @param $withdrawId
     * @return int
     */
    public static function findBitcoinByWithdrawId($withdrawId){
        $withdraw = Withdraw::findOne($withdrawId);
        if(!$withdraw){
            return 0;
        }
        $user = Users::findOne($withdraw['userId']);
        if($user){
            return $user['bitcoin'];
        }else{
            return 0;
        }
    }

    /**
     * 根据推荐码查询用户
     * @param $recommendCode
     * @return bool|mixed
     */
    public static function findUserByRecommendCode($recommendCode){
        $user = Users::find()
            ->where(['recommendCode'=>$recommendCode,'state'=>Users::STATE_NORMAL])
            ->one();
        if($user){
            return $user;
        }
        return false;
    }

    /**
     * 查询推荐人的昵称
     * @param $recommendUserId
     * @return string
     */
    public static function findRecommendUserName($recommendUserId){
        $user = Users::findOne(['userId'=>$recommendUserId,'state'=>Users::STATE_NORMAL]);
        if($user){
            return $user['nickname'];
        }else{
            return "无推荐人";
        }
    }

    /**
     * 查询推荐人
     * @param $recommendUserId
     * @return null|static
     */
    public static function findRecommendUser($recommendUserId){
        return $user = Users::findOne(['userId'=>$recommendUserId]);
    }

    /**
     * 更新用户状态
     * @param $userId
     * @param $newState
     */
    public static function updateState($userId,$newState){
        $user = Users::findOne($userId);
        $user->state = $newState;
        $user->save();
    }

    /**
     * 查询被推荐的人
     * @param $userId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findBeRecommend($userId){
        return Users::find()
            ->where(['recommendUserID'=>$userId])
            ->all();
    }

    /**
     * 分配云豆
     * @param $fromUserId   //大客户ID
     * @param $toUserId     //分配给的用户ID
     * @param $bitcoin
     */
    public static function distributeBitcoin($fromUserId,$toUserId,$bitcoin){
        IncomeConsume::saveRecord($toUserId,$bitcoin,UsageMode::USAGE_DISTRIBUTE_INCOME,IncomeConsume::TYPE_INCOME,$fromUserId);    //分配用户收入云豆
        IncomeConsume::saveRecord($fromUserId,$bitcoin,UsageMode::USAGE_DISTRIBUTE_CONSUME,IncomeConsume::TYPE_CONSUME,$toUserId);    //大客户支出云豆
    }

}
