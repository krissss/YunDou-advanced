<?php
/** 报名的表单 */
namespace frontend\models\forms;

use common\functions\DateFunctions;
use common\models\Info;
use common\models\MajorJob;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

class SignUpForm extends Model
{
    public $IDCard;
    public $realName;
    public $password;
    public $password_repeat;
    public $cellphone;
    public $education;
    public $major;
    public $workTime;
    public $technical;
    public $signUpMajor;
    public $company;
    public $findPasswordQuestion;
    public $findPasswordAnswer;

    public $headImg;
    public $IDCardImg1;
    public $IDCardImg2;

    private $userId;

    public function rules()
    {
        return [
            [['IDCard','realName','password','password_repeat','cellphone','education','major','workTime'
                ,'technical','company','findPasswordQuestion','findPasswordAnswer','headImg','IDCardImg1','IDCardImg2'], 'required'],
            [['IDCard'], 'string','length'=>[18,18]],
            [['IDCard'], 'validateIDCard'],
            [['realName'], 'match', 'pattern' => '/^[\x{4e00}-\x{9fa5}]+$/u','message'=>'{attribute}必须是中文'],
            [['password'], 'match', 'pattern' =>'/^[a-zA-Z][a-zA-Z0-9_]{6,16}$/','message'=>'{attribute}必须为字母开头6-16位'],
            [['password_repeat'], 'compare','compareAttribute'=>'password','operator' => '==','message'=>'两次密码输入不一致'],
            [['cellphone'], 'match', 'pattern' =>'/1[3458]{1}\d{9}$/','message'=>'{attribute}不合法'],
            [['education','major','technical','signUpMajor','company','findPasswordQuestion','findPasswordAnswer'], 'string'],
            [['workTime'], 'safe'],
            [['headImg','IDCardImg1','IDCardImg2'],'image','extensions' => 'png, jpg', 'maxSize' => 100*1024],
            [['headImg'],'image','extensions' => ['png', 'jpg'],
                'minWidth' => 102, 'maxWidth' => 102,
                'minHeight' => 126, 'maxHeight' => 126,
            ],
        ];
    }

    public function validateIDCard($attribute){
        if(Info::findByIDCard($this->IDCard)){
            $this->addError($attribute, '该身份证已经记录过');
        }
    }

    public function attributeLabels()
    {
        return [
            'IDCard' => '身份证号码',
            'realName' => '姓名',
            'password' => '拟设定密码',
            'password_repeat' => '确认密码',
            'cellphone' => '手机号',
            'education' => '学历',
            'major' => '所学专业',
            'workTime' => '参加工作时间',
            'technical' => '技术职称',
            'signUpMajor' => '报考专业岗位',
            'company' => '工作单位',
            'findPasswordQuestion' => '密码找回问题',
            'findPasswordAnswer' => '问题答案',
            'headImg' => '个人照片',
            'IDCardImg1' => '身份证正面',
            'IDCardImg2' => '身份证反面',
        ];
    }

    public function init(){
        /** @var $user \common\models\Users */
        $user = Yii::$app->session->get('user');
        if($user){
            $this->userId = $user['userId'];
            $this->signUpMajor = MajorJob::findNameByMajorJobId($user['majorJobId']);
            $this->realName = $user['realname'];
            $this->cellphone = $user['cellphone'];
            $this->company = $user['company'];
        }
    }

    protected function saveImage($signUpForm,$imageName){
        $this->$imageName = UploadedFile::getInstance($signUpForm, $imageName);
        $imgPath = 'uploads/' . $this->IDCard .'_'.$imageName. '_'.rand(10,99). '.'.$this->$imageName->extension;
        $this->$imageName->saveAs($imgPath);
        return $imgPath;
    }

    public function loadPost($signUpForm){
        $requestSignUpForm = Yii::$app->request->post('SignUpForm');
        $this->IDCard = $requestSignUpForm['IDCard'];
        $this->realName = $requestSignUpForm['realName'];
        $this->password = $requestSignUpForm['password'];
        $this->password_repeat = $requestSignUpForm['password_repeat'];
        $this->cellphone = $requestSignUpForm['cellphone'];
        $this->education = $requestSignUpForm['education'];
        $this->major = $requestSignUpForm['major'];
        $this->workTime = $requestSignUpForm['workTime'];
        $this->technical = $requestSignUpForm['technical'];
        $this->signUpMajor = $requestSignUpForm['signUpMajor'];
        $this->company = $requestSignUpForm['company'];
        $this->findPasswordQuestion = $requestSignUpForm['findPasswordQuestion'];
        $this->findPasswordAnswer = $requestSignUpForm['findPasswordAnswer'];
        $this->headImg = $this->saveImage($signUpForm,'headImg');
        $this->IDCardImg1 = $this->saveImage($signUpForm, 'IDCardImg1');
        $this->IDCardImg2 = $this->saveImage($signUpForm, 'IDCardImg2');
        return true;
    }

    public function record(){
        $info = new Info();
        $info->userId = $this->userId;
        $info->IDCard = $this->IDCard;
        $info->realName = $this->realName;
        $info->password = $this->password;
        $info->cellphone = $this->cellphone;
        $info->education = $this->education;
        $info->major = $this->major;
        $info->workTime = $this->workTime;
        $info->technical = $this->technical;
        $info->signUpMajor = $this->signUpMajor;
        $info->company = $this->company;
        $info->findPasswordQuestion = $this->findPasswordQuestion;
        $info->findPasswordAnswer = $this->findPasswordAnswer;
        $info->headImg = $this->headImg;
        $info->IDCardImg1 = $this->IDCardImg1;
        $info->IDCardImg2 = $this->IDCardImg2;
        $info->createDate = DateFunctions::getCurrentDate();
        if(!$info->save()){
            throw new Exception("Sign Up Info save error");
        }
    }

}