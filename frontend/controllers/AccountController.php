<?php

namespace frontend\controllers;

use common\models\Users;
use frontend\filters\OpenIdFilter;
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

    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],
        ];
    }

    /**
     * 实名认证
     */
    public function actionRegister(){
        /*$currentUrl = urlencode(Url::to(['account/index'],true));
        $appid_verify = md5("wxcf0cd66d7cdf07088bcd5a776d588ff7dc2f66c10b7efd11");
        $url = "http://www.weixingate.com/gate.php?back=$currentUrl&force=1&appid=wxcf0cd66d7cdf0708&appid_verify=$appid_verify";
        $fp=file_get_contents($url) or die("can not open $url");
        print_r($fp);*/
        echo Yii::$app->session->get('openId')."哈哈哈";
        $registerForm = new RegisterForm();
        if($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()){
            $registerForm->register();
            $url = Url::previous(); //获取前面记住的url
            if($url){
                return $this->redirect($url);
            }else{
                echo "恭喜您，注册成功！";
                exit;
            }
        }
        $majorJobs = MajorJob::findAllForJson();
        $provinces = Province::findAllForJson();
        return $this->render('register',[
            'registerForm' => $registerForm,
            'majorJobs' => $majorJobs,
            'provinces' => $provinces
        ]);
    }

    /**
     * 网页获取授权，暂未使用
     * @param $userInfo
     * @return string|\yii\web\Response
     */
    public function actionSingUp($userInfo){
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
                    //return $this->redirect(['account/sign-up','userInfo'=>$userInfo]);
                }else{
                    echo "用户不存在引起的错误，请联系客服";
                    exit;
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