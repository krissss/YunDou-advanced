<?php

namespace frontend\controllers;

use common\functions\CommonFunctions;
use common\models\IncomeConsume;
use common\models\Invoice;
use common\models\Pay;
use common\models\PracticeRecord;
use common\models\Scheme;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\functions\SMS;
use frontend\models\forms\ApplyInvoiceForm;
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
        $user = Yii::$app->session->get('user');
        $incomeConsumes = IncomeConsume::findByUser($user['userId']);
        return $this->render('index',[
            'incomeConsumes' => $incomeConsumes
        ]);
    }

    /** 充值记录 */
    public function actionPayRecord(){
        $user = Yii::$app->session->get('user');
        $payRecords = Pay::findByUser($user['userId']);
        return $this->render('pay-record',[
            'payRecords' => $payRecords
        ]);
    }

    /** 发票申请 */
    public function actionInvoiceApply(){
        $applyInvoiceForm = new ApplyInvoiceForm();
        CommonFunctions::createAlertMessage("为了更好的为您提供服务，请认真填写相关信息！","info");
        if($applyInvoiceForm->load(Yii::$app->request->post()) && $applyInvoiceForm->validate()){
            if($applyInvoiceForm->record()) {
                CommonFunctions::createAlertMessage("您的申请已经记录，请耐心等待管理员审核和配送", "success");
            }
        }
        $user = Yii::$app->session->get('user');
        $invoices = Invoice::findAllByUser($user['userId']);
        return $this->render('invoice-apply',[
            'applyInvoiceForm' => $applyInvoiceForm,
            'invoices' => $invoices
        ]);
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
        $request = Yii::$app->request;
        if($request->isPost){   //调用微信支付页面
            $rechargeForm->money = $request->post('money');
            if($rechargeForm->validate()){
                $order = $rechargeForm->generateOrder();
                return $this->renderAjax('recharge-order',[
                    'order' => $order
                ]);
            }
        }
        $user = Yii::$app->session->get('user');
        $scheme = Scheme::findPayScheme();  //获取充值方案
        Yii::$app->session->set("scheme",$scheme);  //将充值方式存入，在后面记录用户充值记录的时候使用
        $proportion = intval($scheme['getBitcoin'])/intval($scheme['payMoney']);    //充值比例
        if($request->get('type')=='over'){  //支付成功后
            Pay::recordOne();   //记录充值记录+用户云豆数增加
            CommonFunctions::createAlertMessage("充值成功","success");
        }else{
            CommonFunctions::createAlertMessage("当前充值比例：1:$proportion","info");
        }
        $leftBitcoin = Users::findBitcoin($user['userId']); //剩余的云豆
        return $this->render('recharge',[
            'rechargeForm' => $rechargeForm,
            'leftBitcoin' => $leftBitcoin,
            'proportion' => $proportion
        ]);
    }

    /** 接收微信支付异步通知回调地址，貌似不起作用 */
    public function actionNotify(){
        require_once "./../functions/PayNotifyCallBack.php";
        $notify = new \PayNotifyCallBack();
        $notify->Handle(false);
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

    /** 用户注册获取验证码 */
    public function actionGetYzm(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $mobile = $request->post('mobile');
            $yzm = mt_rand(100000,999999);
            Yii::$app->cache->set($mobile,$yzm,600);    //验证码缓存10分钟
            $result = json_decode(SMS::send_sms($yzm,$mobile));
            if($result->code == 0 && $result->msg == 'OK'){
                return true;
            }else{
                return json_encode($result);    //调试用，js端上线后不应该弹出
            }
        }
        throw new Exception("非法获取");
    }
}