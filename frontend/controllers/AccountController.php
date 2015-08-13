<?php

namespace frontend\controllers;

use common\models\IncomeConsume;
use common\models\Pay;
use common\models\PracticeRecord;
use common\models\UsageMode;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\functions\WeiXinFunctions;
use frontend\models\forms\RechargeForm;
use frontend\models\forms\RegisterForm;
use Yii;
use common\models\MajorJob;
use common\models\Province;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\Controller;

class AccountController extends Controller
{
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
            $url = Url::previous("register"); //获取前面记住的url
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
        $rechargeForm = new RechargeForm();
        if($rechargeForm->load(Yii::$app->request->post()) && $rechargeForm->validate()){
            //
        }
        return $this->render('recharge',[
            'rechargeForm' => $rechargeForm
        ]);
    }

    /** 我要推荐 */
    public function actionRecommend(){
        return $this->render('recommend');
    }

    public function actionPay(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $session = Yii::$app->session;
            $user = $session->get('user');
            $leftBitcoin = Users::findBitcoin($user['userId']);
            $scheme = $session->get('practice-scheme');
            if($leftBitcoin < $scheme['payBitcoin']){
                return '您的云豆余额不足';
            }
            IncomeConsume::saveRecord($user['userId'],$scheme['payBitcoin'],UsageMode::USAGE_PRACTICE,IncomeConsume::TYPE_CONSUME);
            PracticeRecord::saveRecord($user['userId'],$scheme);
            return true;
        }
        throw new Exception("非法支付");
    }
}