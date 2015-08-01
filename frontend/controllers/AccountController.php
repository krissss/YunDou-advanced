<?php

namespace frontend\controllers;

use frontend\functions\WeiXinFunctions;
use frontend\models\forms\RegisterForm;
use Yii;
use common\models\MajorJob;
use common\models\Province;
use yii\helpers\Url;
use yii\web\Controller;

class AccountController extends Controller
{
    public $layout = 'practice';

    /**
     * 实名认证
     */
    public function actionRegister(){
        $request = Yii::$app->request;
        $state = $request->get("state");
        if($state){
            echo Url::current();
            exit;
        }
        $redirect_uri = urlencode(Url::current());
        $url = WeiXinFunctions::getAuthorizeUrl($redirect_uri);
        return $this->redirect($url);
        $registerForm = new RegisterForm();
        if($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()){
            //$registerForm->register();
            exit;
        }
        $majorJobs = MajorJob::findAllForJson();
        $provinces = Province::findAllForJson();
        return $this->render('register',[
            'registerForm' => $registerForm,
            'majorJobs' => $majorJobs,
            'provinces' => $provinces
        ]);
    }

    public function actionDoRegister(){
        $registerForm = new RegisterForm();
        if($registerForm->load(Yii::$app->request->post())){
           echo $registerForm->address;
        }
        exit;
        return $this->render('newCodeReading',[
            'model'=>$model,
            'courses'=>$courses,
        ]);
        $request = Yii::$app->request;
        $nickname = $request->post("nickname");
        $weixin = $request->post("weixin");
        $cellphone = $request->post("cellphone");
        $yzm = $request->post("yzm");
        $company = $request->post("company");
        $address = $request->post("address");
        $province = $request->post("province");
        $majorJobId = $request->post("majorJobId");
        $tjm = $request->post("tjm");
    }
}