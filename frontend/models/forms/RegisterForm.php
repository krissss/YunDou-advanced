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
    public $nickname;
    public $realname;
    public $provinceId;
    public $majorJobId;
    public $cellphone;
    public $company;
    public $address;
    public $yzm;
    public $tjm;

    public function rules()
    {
        return [
            [['nickname','realname','majorJobId',  'provinceId','cellphone', 'company', 'address', 'yzm', 'tjm'], 'required'],
            [['majorJobId', 'provinceId'], 'integer'],
            [['nickname', 'company', 'address'], 'string', 'max' => 50],
            [['cellphone'], 'number','min'=>10000000000, 'max'=>19999999999],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'provinceId' => '考试区域',
            'majorJobId' => '专业类型',
            'cellphone' => '手机号',
            'company' => '工作单位',
            'address' => '家庭住址',
            'yzm' => '验证码',
            'tjm' => '推荐码',
        ];
    }

    public function init(){
        $openId = Yii::$app->session->get('openId');
        /** @var $user \common\models\Users */
        $user = Users::findByWeiXin($openId);
        if($user){
            $this->nickname = $user->nickname;
            $this->realname = $user->realname;
            $this->provinceId = $user->provinceId;
            $this->majorJobId = $user->majorJobId;
            $this->cellphone = $user->cellphone;
            $this->company = $user->company;
            $this->address = $user->address;
        }
    }

    public function register(){
        $newUserFlag = false;
        $openId = Yii::$app->session->get('openId');
        $user = Users::findByWeiXin($openId);
        if(!$user){ //如果用户不存在，即关注的时候没有把微信的相关信息存入
            $user = new Users();
            $user->weixin = $openId;
            $newUserFlag = true;
        }
        $user->nickname = $this->nickname;
        $user->realname = $this->realname;
        $user->provinceId = $this->province;
        $user->majorJobId = $this->majorJobId;
        $user->cellphone = $this->cellphone;
        $user->company = $this->company;
        $user->address = $this->address;
        $user->bitcoin = 0;
        $user->registerDate = DateFunctions::getCurrentDate();
        $user->role = Users::ROLE_A;
        $user->recommendCode = CommonFunctions::createRecommendCode();
        $user->recommendUserID = "";
        if($newUserFlag){   //如果是新用户
            if(!$user->save()){
                throw new Exception("RegisterForm register Save Error");
            }
        } else{
            if(!$user->update()){
                throw new Exception("RegisterForm register update Error");
            }
        }
        Yii::$app->session->set('user',$user);
    }


}