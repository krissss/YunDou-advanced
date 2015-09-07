<?php
/** 添加或更新2A和3A用户的表单 */
namespace backend\models\forms;

use common\functions\CommonFunctions;
use common\functions\DateFunctions;
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

    public $role;
    public $roleName;

    public function rules()
    {
        return [
            [['departmentId','username','nickname','address','realname','cellphone'], 'required'],
            [['userId','departmentId','role'], 'integer'],
            [['username'], 'string','min'=>5],
            [['username'], 'match','pattern'=>'/^[A-Za-z0-9]+$/','message'=>'只允许数字和字母'],
            [['username'], 'validateUsername'],
            [['username', 'email', 'weixin', 'nickname', 'realname', 'address'], 'string', 'max' => 50],
            [['cellphone'], 'match', 'pattern' =>'/1[3458]{1}\d{9}$/','message'=>'{attribute}不合法'],
            [['email'],'email'],
            [['qq'], 'string', 'max' => 12]
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

    public function initRoleName(){
        if($this->role == Users::ROLE_AA){
            $this->roleName = "AA级伙伴";
        }elseif($this->role == Users::ROLE_AAA){
            $this->roleName = "AAA级伙伴";
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
            $user = Users::findOne($id);
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
        if(!$this->userId){
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
            $user->registerDate = DateFunctions::getCurrentDate();
        }else{
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
        }
    }



}