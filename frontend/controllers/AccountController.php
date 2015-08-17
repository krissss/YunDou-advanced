<?php

namespace frontend\controllers;

use common\functions\CommonFunctions;
use common\models\IncomeConsume;
use common\models\PracticeRecord;
use common\models\Scheme;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\functions\SMS;
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
        CommonFunctions::createAlertMessage("为了更好的为您提供服务，请认真进行实名认证！","info");
        if($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()){
            $registerForm->register();
            CommonFunctions::createAlertMessage("恭喜您，注册成功","success");
            $url = Url::previous("register"); //获取前面记住的url
            if($url){
                return $this->redirect($url);
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

    /** 在线练习支付 */
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
            IncomeConsume::saveRecord($user['userId'],$scheme['payBitcoin'],Scheme::USAGE_PRACTICE,IncomeConsume::TYPE_CONSUME);
            PracticeRecord::saveRecord($user['userId'],$scheme);
            return true;
        }
        throw new Exception("非法支付");
    }

    public function actionGetYzm(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $mobile = $request->post('mobile');
            $yzm = mt_rand(100000,999999);
            Yii::$app->cache->set($mobile,$yzm,600);    //验证码缓存10分钟
            $text="【云豆在线学习】您的验证码是".$yzm."。如非本人操作，请忽略本短信";
            $result = json_decode(SMS::send_sms($text,$mobile));
            if($result->code == 0 && $result->msg == 'OK'){
                return true;
            }else{
                return json_encode($result);    //调试用，js端上线后不应该弹出
            }
        }
        throw new Exception("非法获取");
    }
}