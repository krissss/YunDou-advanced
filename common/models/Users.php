<?php

namespace common\models;

use frontend\functions\WeiXinFunctions;
use Yii;
use yii\base\Exception;

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
 * @property string $remark
 */
class Users extends \yii\db\ActiveRecord
{
    const ROLE_A = 1;
    const ROLE_AA = 2;
    const ROLE_AAA = 3;
    const ROLE_ADMIN = 10;

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
            [['majorJobId', 'bitcoin', 'provinceId', 'cityId', 'role', 'recommendUserID'], 'integer'],
            [['registerDate'], 'safe'],
            [['username', 'password', 'email', 'weixin', 'nickname', 'realname', 'company', 'address'], 'string', 'max' => 50],
            [['userIcon'], 'string', 'max' => 255],
            [['sex'], 'string', 'max' => 2],
            [['cellphone'], 'string', 'max' => 11],
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
            'remark' => 'Remark',
        ];
    }

    public function getProvince(){
        return $this->hasOne(Province::className(),['provinceId'=>'provinceId']);
    }

    public function getMajorJob(){
        return $this->hasOne(MajorJob::className(),['majorJobId'=>'majorJobId']);
    }

    public function getRoleName(){
        switch($this->role){
            case 1: $msg = "A级"; break;
            case 2: $msg = "AA级"; break;
            case 3: $msg = "AAA级"; break;
            case 10: $msg = "管理员"; break;
            default: $msg = "未定义";
        }
        return $msg;
    }

    public function getRecommendUser(){
        return $this->hasOne(Users::className(),['userId'=>'recommendUserID']);
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
     * 用户添加云豆
     * @param $userId
     * @param $bitcoin
     * @throws Exception
     * @throws \Exception
     */
    public static function addBitcoin($userId,$bitcoin){
        $user = Users::findOne($userId);
        $user->bitcoin+=intval($bitcoin);
        if(!$user->update()){
            throw new Exception("Users update error");
        }
    }

    /**
     * 根据推荐码查询用户
     * @param $recommendCode
     * @return bool|mixed
     */
    public static function findUserByRecommendCode($recommendCode){
        $user = Users::find()
            ->where(['recommendCode'=>$recommendCode])
            ->one();
        if($user){
            return $user;
        }
        return false;
    }

    public static function findRecommendUserName($userId){
        $user = Users::findOne($userId);
        if($user){
            return $user['nickname'];
        }else{
            return "无推荐人";
        }
    }
}
