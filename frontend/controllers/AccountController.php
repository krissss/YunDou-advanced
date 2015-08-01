<?php

namespace frontend\controllers;

use common\models\Users;
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
        if($state == 'YUN'){    //需要与getAuthorizeUrl($redirect_uri)中定义的一致，用户认证后回调操作如下
            $code = $request -> get("code");
            if($code){  //code存在表示用户允许授权
                $result = WeiXinFunctions::getAuthAccessToken($code);
                $openId = $result->openid;
                $access_token = $result->access_token;
                $userInfo = Users::findByWeiXin($openId);
                if($userInfo){
                    return $this->redirect(['account/sign-up','userInfo'=>$userInfo]);
                }
            }else{
                echo "用户不允许授权";
                exit;
            }
        }
        $redirect_uri = urlencode(Url::to(["account/register"],true));
        $url = WeiXinFunctions::getAuthorizeUrl($redirect_uri);
        return $this->redirect($url);
    }

    public function actionSingUp($userInfo){
        $registerForm = new RegisterForm();
        if($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()){
            //$registerForm->register();
            exit;
        }
        $majorJobs = MajorJob::findAllForJson();
        $provinces = Province::findAllForJson();
        return $this->render('register',[
            'userInfo' => $userInfo,
            'registerForm' => $registerForm,
            'majorJobs' => $majorJobs,
            'provinces' => $provinces
        ]);
    }
    /*public function actionDoRegister(){
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
    }*/
}