<?php

namespace frontend\models\forms;

use Yii;
use common\models\Users;
use common\functions\CommonFunctions;
use common\functions\DateFunctions;
use yii\base\Exception;
use yii\base\Model;

class UpdateInfoForm extends Model
{
    public $nickname;
    public $realname;
    public $provinceId;
    public $majorJobId;
    public $company;
    public $address;

    public function rules()
    {
        return [
            [['nickname','realname','majorJobId', 'provinceId'], 'required'],
            [['majorJobId', 'provinceId'], 'integer'],
            [['nickname', 'company', 'address'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'provinceId' => '考试区域',
            'majorJobId' => '专业类型',
            'company' => '工作单位',
            'address' => '家庭住址',
        ];
    }

    public function init(){
        /** @var $user \common\models\Users */
        $user = Yii::$app->session->get('user');
        if($user){
            $this->nickname = $user->nickname;
            $this->realname = $user->realname;
            $this->provinceId = $user->provinceId;
            $this->majorJobId = $user->majorJobId;
            $this->company = $user->company;
            $this->address = $user->address;
        }
    }

    public function update(){
        $user = Yii::$app->session->get('user');
        /** @var $user \common\models\Users */
        $user = Users::findOne($user['userId']);
        $user->nickname = $this->nickname;
        $user->realname = $this->realname;
        $user->provinceId = $this->provinceId;
        $user->majorJobId = $this->majorJobId;
        $user->company = $this->company;
        $user->address = $this->address;
        if(!$user->save()){
            throw new Exception("UpdateInfoForm update Save Error");
        }
        Yii::$app->session->set('user',$user);
    }


}