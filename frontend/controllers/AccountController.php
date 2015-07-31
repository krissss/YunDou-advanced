<?php

namespace frontend\controllers;

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
        $url = urlencode(Url::to(['practice/index']));
        $responseType = 200;
        $scope = 'snsapi_base';
        return $this->redirect('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxcf0cd66d7cdf0708&redirect_uri='.$url.'&response_type='.$responseType.'&scope='.$scope.'&state=STATE#wechat_redirect');
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