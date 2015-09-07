<?php
/** 报名的表单 */
namespace frontend\models\forms;

use common\functions\CommonFunctions;
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
    public $educationImg;

    private $userId;

    public function rules()
    {
        return [
            [['IDCard','realName','cellphone','education','major','workTime','technical','company',
                'findPasswordQuestion','findPasswordAnswer','headImg','educationImg'], 'required'],
            [['IDCard'], 'string','length'=>[18,18]],
            [['IDCard'], 'validateIDCard'],
            [['realName'], 'match', 'pattern' => '/^[\x{4e00}-\x{9fa5}]+$/u','message'=>'{attribute}必须是中文'],
            [['cellphone'], 'match', 'pattern' =>'/1[3458]{1}\d{9}$/','message'=>'{attribute}不合法'],
            [['education','major','technical','signUpMajor','company','findPasswordQuestion','findPasswordAnswer'], 'string'],
            [['company'], 'string','min'=>2],
            [['workTime'], 'safe'],
            [['headImg'],'image','extensions' => ['png', 'jpg'],
                'minWidth' => 102, 'maxWidth' => 102,
                'minHeight' => 126, 'maxHeight' => 126,
            ],
            [['educationImg'],'image','extensions' => ['png', 'jpg'],
                'minWidth' => 650, 'maxWidth' => 650,
                'minHeight' => 500, 'maxHeight' => 500,
            ],
        ];
    }

    public function validateIDCard($attribute){
        if(Info::checkUserRecordOrPass($this->userId)){
            $this->addError($attribute, '该身份证已经记录过或已报名成功');
        }
    }

    public function attributeLabels()
    {
        return [
            'IDCard' => '身份证号码',
            'realName' => '姓名',
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
            'educationImg' => '学历证书扫描件'
        ];
    }

    public function init(){
        /** @var $user \common\models\Users */
        $user = Yii::$app->session->get('user');
        if($user){
            $info = Info::findRefusedByUserId($user['userId']);
            if($info){
                $this->userId = $info->userId;
                $this->IDCard = $info->IDCard;
                $this->realName = $info->realName;
                $this->cellphone = $info->cellphone;
                $this->education = $info->education;
                $this->major = $info->major;
                $this->workTime = $info->workTime;
                $this->technical = $info->technical;
                $this->signUpMajor = $info->signUpMajor;
                $this->company = $info->company;
                $this->findPasswordQuestion = $info->findPasswordQuestion;
                $this->findPasswordAnswer = $info->findPasswordAnswer;
            }else{
                $this->userId = $user['userId'];
                $this->signUpMajor = MajorJob::findNameByMajorJobId($user['majorJobId']);
                $this->realName = $user['realname'];
                $this->cellphone = $user['cellphone'];
                $this->company = $user['company'];
            }
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
        $this->educationImg = $this->saveImage($signUpForm, 'educationImg');
        return true;
    }

    public function record(){
        $user = Yii::$app->session->get('user');
        $info = Info::findRefusedByUserId($user['userId']);
        if(!$info){
            $info = new Info();
            $info->password = CommonFunctions::createRandPassword();
        }
        $info->userId = $this->userId;
        $info->IDCard = $this->IDCard;
        $info->realName = $this->realName;
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
        $info->educationImg = $this->educationImg;
        $info->createDate = DateFunctions::getCurrentDate();
        $info->state = Info::STATE_RECORD;
        if(!$info->save()){
            throw new Exception("Sign Up Info save error");
        }
    }

}