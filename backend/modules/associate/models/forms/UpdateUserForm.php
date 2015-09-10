<?php

namespace backend\modules\associate\models\forms;

use common\models\BankCard;
use Yii;
use yii\base\Model;
use common\models\Users;

class UpdateUserForm extends Model{
    public $username;
    public $nickname;
    public $realname;
    public $cellphone;
    public $weixin;
    public $qq;
    public $address;
    public $bankName;
    public $cardName;
    public $cardNumber;
    public $email;


    public function rules(){
        return [
            [['username',  'nickname','realname','weixin','address','bankName','email'], 'string', 'max' => 50],
            [['cellphone'],'string','min'=>11,'max'=>11],
            [['qq'],'string','max'=>12],
            [['cardNumber'], 'string', 'max' => 25],
            [['cardName'], 'string', 'max' => 20]
        ];
    }

    public function attributeLabels(){
        return [
            'username'=>'登录名称',
            'nickname'=>'昵称',
            'raalname'=>'联系人',
            'cellphone'=>'电话',
            'weixin'=>'微信',
            'qq' =>'QQ',
            'address'=>'地址',
            'bankName'=>'开户行',
            'cardName'=>'账号名称',
            'cardNumber'=>'银行卡号',
            'email'=>'邮箱',
        ];
    }

    /**
     * 更新用户基本信息
     * @param $userId
     */
    public function updateUser($userId){
        /** @var $user \common\models\Users */
        $user = Users::findOne($userId);
        $user->email = $this->email;
        $user->cellphone = $this->cellphone;
        $user->weixin = $this->weixin;
        $user->qq = $this->qq;
        $user->nickname = $this->nickname;
        $user->realname = $this->realname;
        $user->address = $this->address;
        $user->save();
    }

    public function updateBank($userId){
        /** @var $bankCard \common\models\BankCard */
        $bankCard =BankCard::findOne(['userId'=>$userId]);
        if(!$bankCard){
            $bankCard = new BankCard();
            $bankCard->userId = $userId;
            $bankCard->bankName = $this->bankName;
            $bankCard->cardNumber = $this->cardNumber;
            $bankCard->cardName = $this->cardName;
            $bankCard->save();
        }else{
            $bankCard->bankName = $this->bankName;
            $bankCard->cardNumber = $this->cardNumber;
            $bankCard->cardName = $this->cardName;
            $bankCard->save();
        }
    }

    /**
     *填写基本信息内容
     * @param $id
     * @return UpdateUserForm
     */
    public static function initUser($id){
        /** @var $user \common\models\Users */
        $user = Users::findOne($id);
        $form = new UpdateUserForm();
        $form->username = $user->username;
        $form->nickname = $user->nickname;
        $form->realname = $user->realname;
        $form->cellphone = $user->cellphone;
        $form->weixin = $user->weixin;
        $form->qq = $user->qq;
        $form->address = $user->address;
        $form->bankName = $user->bankCard['bankName'];
        $form->cardName = $user->bankCard['cardName'];
        $form->cardNumber = $user->bankCard['cardNumber'];
        $form->email = $user->email;
        return $form;
    }

}