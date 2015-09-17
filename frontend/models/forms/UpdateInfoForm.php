<?php

namespace frontend\models\forms;

use common\models\Collection;
use common\models\CurrentTestLibrary;
use common\models\ErrorQuestion;
use common\models\MajorJob;
use Yii;
use common\models\Users;
use common\functions\CommonFunctions;
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
        $majorJob = MajorJob::findOne($this->majorJobId);
        if($this->provinceId!=$majorJob['provinceId']){
            CommonFunctions::createAlertMessage("专业岗位与所处省份不一致，请重新选择","error");
            return false;
        }
        //修改省份或专业岗位，需要清除用户的在线练习相关信息
        if($this->provinceId!=$user['provinceId'] || $this->majorJobId!=$user['majorJobId']){
            CurrentTestLibrary::deleteAll(['userId'=>$user['userId']]); //删除当前记录
            ErrorQuestion::deleteAll(['userId'=>$user['userId']]);  //删除错题记录
            Collection::deleteAll(['userId'=>$user['userId']]); //删除收藏
        }
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
        return true;
    }


}