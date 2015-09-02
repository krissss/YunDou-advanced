<?php
/** 报名 */
namespace frontend\controllers;

use common\functions\CommonFunctions;
use common\models\Info;
use frontend\filters\OpenIdFilter;
use frontend\models\forms\SignUpForm;
use yii\web\Controller;
use Yii;

class SignUpController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $user = Yii::$app->session->get('user');
        if(Info::checkUserRecordOrPass($user['userId'])){    //判断用户是否已经填报过
            return $this->redirect(['sign-up/over']);
        }
        $signUpForm = new SignUpForm();
        CommonFunctions::createAlertMessage("‘帮我报名’是‘云豆讲堂’为用户提供的增值服务，根据用户在线提交的信息，
        由工作人协助在主管部门进行报名。由于牵涉到考试信息的准确性，请您确保提供的信息准确无误。
        由于提交需要用到个人照片（像素：102╳26）与身份证照片（100K以内），请按规定提交。","info");
        if(Yii::$app->request->isPost){
            if($signUpForm->loadPost($signUpForm) && $signUpForm->validate()){
                $signUpForm->record();
                return $this->redirect(['sign-up/over']);
            }
        }
        return $this->render('index',[
            'signUpForm' => $signUpForm
        ]);
    }

    public function actionOver(){
        $user = Yii::$app->session->get('user');
        $info = Info::findByUserId($user['userId']);
        if($info->state == Info::STATE_RECORD){
            CommonFunctions::createAlertMessage("您的报名信息已经记录，请耐心等待管理员帮您填报信息，填报完成可以在线进行查看","info");
        }elseif($info->state == Info::STATE_PASS){
            CommonFunctions::createAlertMessage("管理员已经帮您报名成功，您可以登录网站在线进行查看","success");
        }elseif($info->state == Info::STATE_REFUSE){
            CommonFunctions::createAlertMessage("管理员帮您填写报名信息失败，原因是：".$info->replyContent,"error");
        }else{
            CommonFunctions::createAlertMessage("您的报名信息状态异常，请联系管理员","warning");
        }
        return $this->render('over',[
            'info' => $info
        ]);
    }
}