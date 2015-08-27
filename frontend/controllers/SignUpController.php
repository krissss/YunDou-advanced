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
        CommonFunctions::createAlertMessage("提交报名信息需要用到个人照片（102*126），身份证正反面照（100K以内），请提前准备好","info");
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