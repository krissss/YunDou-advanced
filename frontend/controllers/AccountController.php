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

    /** 我的账户 */
    public function actionIndex(){
        echo "<h1>我的账户，建设中。。。</h1>";
    }

    /** 实名认证*/
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

    /** 我要充值 */
    public function actionRecharge(){
        echo "<h1>我要充值，建设中。。。</h1>";
    }

    /** 我要推荐 */
    public function actionRecommend(){
        echo "<h1>我要推荐，建设中。。。</h1>";
    }
}