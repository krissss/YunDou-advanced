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
    //public $layout = 'practice';

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
        $majorJobs = MajorJob::findAllForObject();
        $provinces = Province::findAllForObject();
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