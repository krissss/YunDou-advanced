<?php
/** 添加或更新2A和3A用户的表单 */
namespace backend\models\forms;

use common\functions\CommonFunctions;
use common\functions\DateFunctions;
use common\models\BankCard;
use common\models\Users;
use yii\base\Exception;
use yii\base\Model;

class AddUserForm extends Model
{
    public $userId;
    public $departmentId;
    public $username;
    public $nickname;
    public $address;
    public $realname;
    public $cellphone;
    public $email;
    public $qq;
    public $weixin;
    public $bankName;
    public $cardNumber;
    public $cardName;
    public $recommendCode;

    public $role;
    public $roleName;

    public function rules()
    {
        return [
            [['departmentId','username','nickname','address','realname','cellphone'], 'required'],
            [['userId','departmentId','role'], 'integer'],
            [['username'], 'string','min'=>5],
            [['cardName'], 'string', 'max' => 20],
            [['cardNumber'], 'number'],
            [['cardNumber'], 'string', 'max' => 25],
            [['username'], 'match','pattern'=>'/^[A-Za-z0-9]+$/','message'=>'只允许数字和字母'],
            [['username'], 'validateUsername'],
            [['username', 'email', 'weixin', 'nickname', 'realname', 'address','bankName'], 'string', 'max' => 50],
            [['cellphone'], 'match', 'pattern' =>'/1[3458]{1}\d{9}$/','message'=>'{attribute}不合法'],
            [['email'],'email'],
            [['qq'], 'string', 'max' => 12],
            [['recommendCode'],'string'],
            [['recommendCode'],'validateRecommendCode'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'departmentId' => '所属部门',
            'username' => '登录名',
            'nickname' => '用户名称',
            'address' => '地址',
            'realname' => '联系人',
            'cellphone' => '手机号',
            'email' => '邮箱',
            'qq' => 'qq',
            'weixin' => '微信',
            'roleName' => '伙伴等级',
            'recommendCode' => '推荐伙伴',
            'bankName' => '开户行',
            'cardNumber' => '卡号',
            'cardName' => '持卡人姓名',
        ];
    }

    public function validateUsername($attribute){
        if(!$this->userId){ //新用户检查登录名是否冲突
            $user = Users::findOne(['username'=>$this->username]);
            if($user){
                CommonFunctions::createAlertMessage("登录名冲突，请重设登录名","error");
                $this->addError($attribute,'登录名冲突，请重设登录名');
            }
        }
    }

    public function validateRecommendCode($attribute){
        if(!$this->userId){ //新用户检查
            if($this->recommendCode){   //如果推荐码存在
                $first = substr($this->recommendCode,0,1);
                if("d"!=$first && "g"!=$first){
                    CommonFunctions::createAlertMessage("填写的推荐码必须是金牌伙伴或者钻石伙伴的","error");
                    $this->addError($attribute,'填写的推荐码必须是金牌伙伴或者钻石伙伴的');
                }
            }
        }
    }

    public function initRoleName(){
        if($this->role == Users::ROLE_AA){
            $this->roleName = "金牌伙伴";
        }elseif($this->role == Users::ROLE_AAA){
            $this->roleName = "钻石伙伴";
        }elseif($this->role == Users::ROLE_BIG){
            $this->roleName = "大客户";
        }else{
            throw new Exception("未知的角色类型");
        }
    }

    /**
     * 初始化form，必须使用
     * @param null $id  配置该参数则role不启用
     * @param null $role    配置该参数则id不启用
     * @return AddUserForm
     * @throws Exception
     */
    public static function initWithIdOrRole($id = null,$role = null){
        $form = new AddUserForm();
        if($id) {
            /** @var $user \common\models\Users*/
            $user = Users::findOne($id);
            /** @var $bank \common\models\bankCard*/
            $bank = BankCard::find()->where(['userId' => $id])->one();
            if($bank) {
                $form->bankName = $bank->bankName;
                $form->cardNumber = $bank->cardNumber;
                $form->cardName = $bank->cardName;
            }
            $form->role = $user->role;
            $form->userId = $id;
            $form->departmentId = $user->departmentId;
            $form->username = $user->username;
            $form->nickname = $user->nickname;
            $form->address = $user->address;
            $form->realname = $user->realname;
            $form->cellphone = $user->cellphone;
            $form->email = $user->email;
            $form->qq = $user->qq;
            $form->weixin = $user->weixin;
            if($form->role == Users::ROLE_BIG){ //大客户要有推荐码
                $form->recommendCode = $user->recommendUser['nickname'];
            }
        }else{
            if($role){
                $form->role = $role;
            }else{
                throw new Exception("role do not exit");
            }
        }
        $form->initRoleName();
        return $form;
    }

    public function recordOne(){
        if(!$this->userId){ //新添加
            $user = new Users();
            $user->bitcoin = 0;
            $user->password = CommonFunctions::encrypt("123456");   //初始密码设置为123456
            $user->state = Users::STATE_NORMAL;
            do{
                if($this->role == Users::ROLE_AA){
                    $recommendCode = CommonFunctions::create2ARecommendCode();
                }elseif($this->role == Users::ROLE_AAA){
                    $recommendCode = CommonFunctions::create3ARecommendCode();
                }elseif($this->role == Users::ROLE_BIG){
                    $recommendCode = CommonFunctions::createBigRecommendCode();
                }else{
                    throw new Exception("未知的角色类型");
                }
            }while(Users::findUserByRecommendCode($recommendCode));
            $user->recommendCode = $recommendCode;
            $recommendUser = Users::findUserByRecommendCode($this->recommendCode);
            $user->recommendUserID = $recommendUser['userId'];
            $user->registerDate = DateFunctions::getCurrentDate();
        }else{  //已存在
            $user = Users::findOne($this->userId);
        }
        $user->role = $this->role;
        $user->departmentId = $this->departmentId;
        $user->username = $this->username;
        $user->nickname = $this->nickname;
        $user->address = $this->address;
        $user->realname = $this->realname;
        $user->cellphone = $this->cellphone;
        $user->email = $this->email;
        $user->qq = $this->qq;
        $user->weixin = $this->weixin;
        if(!$user->save()){
            throw new Exception("add-user-form user save error");
        }else{
            /** @var $bankCard \common\models\BankCard */
            $bankCard = BankCard::findOne(['userId'=>$user->userId]);
            if(!$bankCard){ //如果没有
                $bankCard = new BankCard();
                $bankCard->userId = $user->userId;
                $bankCard->state = BankCard::STATE_DEFAULT;
            }
            $bankCard->bankName = $this->bankName;
            $bankCard->cardNumber = $this->cardNumber;
            $bankCard->cardName = $this->cardName;
            $bankCard->save();
        }
    }



}