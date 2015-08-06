<?php

namespace frontend\models\forms;

use Yii;
use common\models\Users;
use frontend\functions\CommonFunctions;
use frontend\functions\DateFunctions;
use yii\base\Exception;
use yii\base\Model;

class RegisterForm extends Model
{
    public $cellphone;
    public $weixin;
    public $majorJobId;
    public $nickname;
    public $realname;
    public $province;
    public $company;
    public $address;
    public $yzm;
    public $tjm;

    public function rules()
    {
        return [
            [['cellphone', 'majorJobId', 'nickname','realname', 'province', 'company', 'address', 'yzm', 'tjm'], 'required'],
            [['majorJobId', 'province'], 'integer'],
            [['weixin', 'nickname', 'company', 'address'], 'string', 'max' => 50],
            [['cellphone'], 'number','min'=>10000000000, 'max'=>19999999999],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cellphone' => '手机',
            'weixin' => '微信',
            'majorJobId' => '专业类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'province' => '考试区域',
            'company' => '工作单位',
            'address' => '家庭住址',
            'yzm' => '验证码',
            'tjm' => '推荐码',
        ];
    }

    public function register(){
        $session_user = Yii::$app->session->get('user');
        /** @var $user \common\models\Users */
        $user = Users::findOne($session_user['userId']);
        if(!$user){
            throw new Exception("用户未关注微信");
        }
        $user->cellphone = $this->cellphone;
        $user->majorJobId = $this->majorJobId;
        $user->nickname = $this->nickname;
        $user->realname = $this->realname;
        $user->bitcoin = 0;
        $user->provinceId = $this->province;
        $user->company = $this->company;
        $user->address = $this->address;
        $user->registerDate = DateFunctions::getCurrentDate();
        $user->role = Users::ROLE_A;
        $user->recommendCode = CommonFunctions::createRecommendCode();
        $user->recommendUserID = "";
        if(!$user->update()){
            throw new Exception("Users Register Error");
        }
        Yii::$app->session->set('user',$user);
    }


}