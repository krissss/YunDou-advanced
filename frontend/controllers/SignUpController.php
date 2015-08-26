<?php
/** 报名 */
namespace frontend\controllers;

use common\functions\CommonFunctions;
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
        CommonFunctions::createAlertMessage("您的报名信息已经记录，请耐心等待管理员帮您填报信息，填报完成可以在线进行查看","info");

    }
}