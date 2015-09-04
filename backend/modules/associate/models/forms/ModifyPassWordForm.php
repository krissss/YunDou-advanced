<?php

namespace backend\modules\associate\models\forms;

use Yii;
use yii\base\Model;
use common\models\Users;
use common\functions\CommonFunctions;

class ModifyPasswordForm extends Model{
	public $password;
	public $newPassword;
	public $newPasswordAgain;

	public function rules(){
		return [
			[['password','newPassword','newPasswordAgain'],'required'],
			[['password','newPassword','newPasswordAgain'],'string','min'=>6,'max'=>16],
			[['newPassword'],'match', 'pattern' => '/^[0-9]*[A-Za-z_!@#$%^&*()~+|]{1,}[0-9]*$/i', 'message'=>'新密码过于简单'],
			[['newPasswordAgain'],'compare','compareAttribute'=>'newPassword','message'=>'两次密码输入不一致'],
			[['password'],'validatePassword'],
		];
	}

	public function attributeLabels(){
		return [
			'password'=>'原密码',
			'newPassword'=>'新密码',
			'newPasswordAgain'=>'确认新密码',
		];
	}

	public function validatePassword(){
		$user = Yii::$app->session->get('user');
		if($user['password']!=CommonFunctions::encrypt($this->password)){
			$this->addError('password',"原密码错误");
		}
	}

	public function modifyPassword(){
		$user = Yii::$app->session->get('user');
		/** @var $user \common\models\Users */
		$user = Users::findOne($user['userId']);
		$user->password = CommonFunctions::encrypt($this->newPassword);
		$user->save();
	}
}