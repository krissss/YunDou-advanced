<?php
/** 实名认证表单 */
namespace frontend\models\forms;

use common\models\MajorJob;
use Yii;
use common\models\Users;
use common\functions\CommonFunctions;
use common\functions\DateFunctions;
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

    private $recommendUser=false;

    public function rules()
    {
        return [
            [['nickname','realname','majorJobId', 'provinceId','cellphone', 'yzm'], 'required'],
            [['majorJobId', 'provinceId'], 'integer'],
            [['nickname', 'company', 'address'], 'string', 'max' => 50],
            [['tjm'], 'string', 'max' => 15],
            [['cellphone'], 'string','min'=>11, 'max' => 11],
            [['yzm'], 'validateYZM'],
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

    public function validateYZM($attribute){
        $yzm = Yii::$app->cache->get($this->cellphone);
        if($this->yzm != 'test'){   //验证码是test的时候可以直接跳过验证
            if(!$yzm){
                $this->addError($attribute, '手机号与验证码不匹配');
            }elseif($yzm != $this->yzm){
                $this->addError($attribute, '验证码不正确');
            }
        }
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
        $majorJob = MajorJob::findOne($this->majorJobId);
        if($this->provinceId=="" || $this->majorJobId==""){
            CommonFunctions::createAlertMessage("省份或者专业类型不能为空","error");
            return false;
        }
        if($this->provinceId!=$majorJob['provinceId']){
            CommonFunctions::createAlertMessage("专业类型与所处省份不一致，请重新选择","error");
            return false;
        }
        $openId = Yii::$app->session->get('openId');
        $user = Users::findByWeiXin($openId);
        if(!$user){ //如果用户不存在，即关注的时候没有把微信的相关信息存入
            $user = new Users();
            $user->weixin = $openId;
        }
        if(!$user->registerDate || $user->registerDate==0){    //如果用户注册日期不存在或为0，表明用户第一次实名认证
            $user->bitcoin = 0;
            $user->registerDate = DateFunctions::getCurrentDate();
            $user->role = Users::ROLE_A;
            $user->state = Users::STATE_NORMAL;
            do{ //保证生成的推荐码的唯一
                $recommendCode = CommonFunctions::createCommonRecommendCode();
            }while(Users::findUserByRecommendCode($recommendCode));
            $user->recommendCode = $recommendCode;
            if($this->tjm){ //推荐码绑定推荐人
                $this->recommendUser = Users::findUserByRecommendCode($this->tjm);
                if($this->recommendUser){
                    if($this->recommendUser['userId'] != $user['userId']){  //推荐人不是自己
                        $user->recommendUserID =  $this->recommendUser['userId'];
                    }
                }
            }
        }
        $user->nickname = $this->nickname;
        $user->realname = $this->realname;
        $user->provinceId = $this->provinceId;
        $user->majorJobId = $this->majorJobId;
        $user->cellphone = $this->cellphone;
        $user->company = $this->company;
        $user->address = $this->address;
        if(!$user->save()){
            throw new Exception("RegisterForm register Save Error");
        }
        Yii::$app->cache->delete($user->cellphone); //注册成功后将验证码缓存清除
        Yii::$app->session->set('user',$user);
        return true;
    }


}