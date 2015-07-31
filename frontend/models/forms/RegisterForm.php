<?php

namespace frontend\models\forms;

use common\models\Users;
use frontend\functions\DateFunctions;
use yii\base\Model;

class RegisterForm extends Model
{
    public $cellphone;
    public $weixin;
    public $majorJobId;
    public $nickname;
    public $province;
    public $company;
    public $address;
    public $yzm;
    public $tjm;

    public function rules()
    {
        return [
            [['cellphone', 'weixin', 'majorJobId', 'nickname', 'province', 'company', 'address', 'yzm', 'tjm'], 'required'],
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
            'nickname' => '姓名',
            'province' => '考试区域',
            'company' => '工作单位',
            'address' => '家庭住址',
            'yzm' => '验证码',
            'tjm' => '推荐码',
        ];
    }

    public function register(){
        $user = new Users();
        $user->username = "";
        $user->password = "";
        $user->userIcon = "";
        $user->email = "";
        $user->cellphone = $this->cellphone;
        $user->weixin = $this->weixin;
        $user->majorJobId = $this->majorJobId;
        $user->nickname = $this->nickname;
        $user->realname = "";
        $user->introduce = "";
        $user->bitcoin = 0;
        $user->province = $this->province;
        $user->city = "";
        $user->company = $this->company;
        $user->address = $this->address;
        $user->registerDate = DateFunctions::getCurrentDate();
        $user->role = Users::ROLE_A;
        $user->recommendUserID = "";

    }


}