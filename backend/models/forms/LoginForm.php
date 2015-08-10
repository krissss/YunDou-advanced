<?php

namespace backend\models\forms;

use Yii;
use common\models\Users;
use yii\base\Model;
use backend\functions\CommonFunctions;

class LoginForm extends Model{
    public $username;
    public $password;
    public $verifyCode;

    public function rules(){
        return [
            [['username','password'],'required'],
            [['username'], 'string','min'=>5, 'max' => 50],
            [['password'], 'string','min'=>5,  'max' => 50],
            [['verifyCode'], 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
            'verifyCode' => '验证码',
        ];
    }

    public function login(){
        $user = Users::find()->where(['username'=>$this->username,'password'=>CommonFunctions::encrypt($this->password)])->one();
        if($user){
            Yii::$app->session->set('user',$user);
            return true;
        }
        $this->addError('username',"用户名或密码错误");
        $this->addError('password',"用户名或密码错误");
        return false;
    }
}