<?php
/** 修改手机号表单 */
namespace frontend\models\forms;

use Yii;
use common\models\Users;
use common\functions\CommonFunctions;
use common\functions\DateFunctions;
use yii\base\Exception;
use yii\base\Model;

class UpdateCellphoneForm extends Model
{
    public $cellphone;
    public $yzm;
    public $verifyCode;

    public function rules()
    {
        return [
            [['cellphone', 'yzm'], 'required'],
            [['cellphone'], 'string','min'=>11, 'max' => 11],
            [['yzm'], 'validateYZM'],
            [['verifyCode'], 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cellphone' => '手机号',
            'yzm' => '手机验证码',
            'verifyCode' => '图片验证码',
        ];
    }

    public function validateYZM($attribute){
        $yzm = Yii::$app->cache->get($this->cellphone);
        if($this->yzm != 'test'){   //验证码是test的时候可以直接跳过验证
            if(!$yzm){
                $this->addError($attribute, '手机号与验证码不匹配');
            }elseif($yzm != $this->yzm){
                $this->addError($attribute, '手机验证码不正确');
            }
        }
    }

    public function init(){
        /** @var $user \common\models\Users */
        $user = Yii::$app->session->get('user');
        if($user){
            $this->cellphone = $user->cellphone;
        }
    }

    public function update(){
        $user = Yii::$app->session->get('user');
        /** @var $user \common\models\Users */
        $user = Users::findOne($user['userId']);
        $user->cellphone = $this->cellphone;
        if(!$user->save()){
            throw new Exception("UpdateCellphoneForm update Save Error");
        }
        Yii::$app->session->set('user',$user);
    }


}