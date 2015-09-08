<?php

namespace backend\models\forms;

use Yii;
use common\models\Users;
use yii\base\Model;
use common\functions\CommonFunctions;

class LoginForm extends Model{
    public $username;
    public $password;
    public $verifyCode;

    private $_user = false;

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
        $user = Users::find()->where(['username'=>$this->username])->one();
        if($user){
            if($user['state'] == Users::STATE_STOP){    //用户状态被终止
                $this->addError('username',"该用户已被终止登录");
                return false;
            }
            if($user['password'] != CommonFunctions::encrypt($this->password)){
                $this->addError('password',"密码错误");
                return false;
            }
            Yii::$app->session->set('user',$user);
            return true;
        }else{
            $this->addError('username',"用户不存在");
            return false;
        }
    }
}