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
 * @property integer $majorJobId
 * @property string $nickname
 * @property string $realname
 * @property string $introduce
 * @property integer $bitcoin
 * @property integer $province
 * @property integer $city
 * @property string $company
 * @property string $address
 * @property string $registerDate
 * @property integer $role
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
            [['majorJobId', 'bitcoin', 'province', 'city', 'role', 'recommendUserID'], 'integer'],
            [['registerDate'], 'safe'],
            [['username', 'password', 'email', 'weixin', 'nickname', 'realname', 'company', 'address'], 'string', 'max' => 50],
            [['userIcon'], 'string', 'max' => 255],
            [['cellphone'], 'string', 'max' => 11],
            [['introduce', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'username' => 'Username',
            'password' => 'Password',
            'userIcon' => 'User Icon',
            'email' => 'Email',
            'cellphone' => 'Cellphone',
            'weixin' => 'Weixin',
            'majorJobId' => 'Major Job ID',
            'nickname' => 'Nickname',
            'realname' => 'Realname',
            'introduce' => 'Introduce',
            'bitcoin' => 'Bitcoin',
            'province' => 'Province',
            'city' => 'City',
            'company' => 'Company',
            'address' => 'Address',
            'registerDate' => 'Register Date',
            'role' => 'Role',
            'recommendUserID' => 'Recommend User ID',
            'remark' => 'Remark',
        ];
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
            $user->save();
        }
    }

    public static function findByWeiXin($openId){
        return Users::find()
            ->where(['weixin'=>$openId])
            ->one();
    }
}
