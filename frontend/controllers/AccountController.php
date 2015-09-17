<?php

namespace frontend\controllers;

use common\functions\CommonFunctions;
use common\models\IncomeConsume;
use common\models\Invoice;
use common\models\Money;
use common\models\PracticeRecord;
use common\models\Scheme;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\filters\RegisterFilter;
use frontend\functions\SMS;
use frontend\models\forms\ApplyInvoiceForm;
use frontend\models\forms\RegisterForm;
use frontend\models\forms\UpdateCellphoneForm;
use frontend\models\forms\UpdateInfoForm;
use Yii;
use common\models\MajorJob;
use common\models\Province;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;

class AccountController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],[
                'class' => RegisterFilter::className(),
                'except' => ['register','agreement','get-yzm','get-recommend']
            ],
        ];
    }

    /** 我的账户 */
    public function actionIndex(){
        $user = Yii::$app->session->get('user');
        $query = IncomeConsume::find()
            ->where(['userId'=>$user['userId']])
            ->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $incomeConsumes = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'incomeConsumes' => $incomeConsumes,
            'pages' => $pagination
        ]);
    }

    /** 充值记录 */
    public function actionPayRecord(){
        $user = Yii::$app->session->get('user');
        $query = Money::find()
            ->where(['userId'=>$user['userId']])
            ->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $payRecords = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        //print_r($pagination->offset);
        if($pagination->offset > 0){
            Url::remember(['account/pay-record'],'pay-record');
        }
        return $this->render('pay-record', [
            'payRecords' => $payRecords,
            'pages' => $pagination
        ]);
    }

    /** 发票申请 */
    public function actionInvoiceApply(){
        $applyInvoiceForm = new ApplyInvoiceForm();
        CommonFunctions::createAlertMessage("为了更好的为您提供服务，请认真填写相关信息！<br>
        说明：申请金额至少50元起，不得大于可申请金额，且发票将采用到付方式快递给您。","info");
        if($applyInvoiceForm->load(Yii::$app->request->post()) && $applyInvoiceForm->validate()){
            if($applyInvoiceForm->record()) {
                CommonFunctions::createAlertMessage("您的申请已经记录，请耐心等待管理员审核和配送", "success");
            }
        }
        $user = Yii::$app->session->get('user');
        $query = Invoice::find()
            ->where(['userId'=>$user['userId']])
            ->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $invoices = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('invoice-apply', [
            'applyInvoiceForm' => $applyInvoiceForm,
            'invoices' => $invoices,
            'pages' => $pagination
        ]);
    }

    /** 实名认证*/
    public function actionRegister(){
        $majorJobs = MajorJob::findAllForObject();
        $provinces = Province::findAllForObject();
        $user = Yii::$app->session->get('user');
        //已经进行过实名认证的用户
        if($user['majorJobId']!=0 && $user['registerDate']!=null && $user['registerDate']>0){
            CommonFunctions::createAlertMessage("您已经进行过实名认证，可以选择修改部分信息。<br>
            注意：修改考试区域、专业类型信息后，您以前做的相关在线练习、错题、重点题信息等都会重置，请慎重。","info");
            $updateInfoForm = new UpdateInfoForm();
            if($updateInfoForm->load(Yii::$app->request->post()) && $updateInfoForm->validate()){
                if($updateInfoForm->update()){
                    CommonFunctions::createAlertMessage("恭喜您，修改成功","success");
                }
            }
            return $this->render('update-info',[
                'updateInfoForm' => $updateInfoForm,
                'majorJobs' => $majorJobs,
                'provinces' => $provinces
            ]);
        }
        //未进行过实名认证的用户
        $registerForm = new RegisterForm();
        CommonFunctions::createAlertMessage("实名认证主要用于系统内部正确配置相关的题库与模拟试题模型，请如实、认证填写","info");
        if($registerForm->load(Yii::$app->request->post()) && $registerForm->validate()){
            if($registerForm->register()){
                CommonFunctions::createAlertMessage("恭喜您，注册成功","success");
                $url = Url::previous("register"); //获取前面记住的url
                if($url){
                    return $this->redirect($url);
                }
            }
        }
        return $this->render('register',[
            'registerForm' => $registerForm,
            'majorJobs' => $majorJobs,
            'provinces' => $provinces
        ]);
    }

    /** 协议页面 */
    public function actionAgreement(){
        $type = Yii::$app->request->get('type');
        if($type == 'download'){
            if(file_exists('./agreement/yundou_service.pdf')){
                return Yii::$app->response->sendFile('./agreement/yundou_service.pdf');
            }else{
                return "<h1>文件不存在</h1>";
            }
        }
        return $this->render('agreement');
    }

    /** 更新手机号 */
    public function actionUpdateCellphone(){
        $updateCellphoneForm = new UpdateCellphoneForm();
        CommonFunctions::createAlertMessage("您可以在此修改您的手机号！","info");
        if($updateCellphoneForm->load(Yii::$app->request->post()) && $updateCellphoneForm->validate()){
            $updateCellphoneForm->update();
            CommonFunctions::createAlertMessage("恭喜您，修改成功","success");
        }
        return $this->render('update-cellphone',[
            'updateCellphoneForm' => $updateCellphoneForm
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
            $schemeId = $request->post('schemeId');
            /** @var $scheme \common\models\Scheme */
            $scheme = Scheme::findOne($schemeId);
            if(!$scheme){
                return '该方案已停用';
            }
            $leftBitcoin = Users::findBitcoin($user['userId']);
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

    /** 用户验证推荐用户 */
    public function actionGetRecommend(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $recommendCode = $request->post('recommendCode');
            if($recommendCode){
                $recommendUser = Users::findUserByRecommendCode($recommendCode);
                if($recommendUser){
                    $user = Yii::$app->session->get('user');
                    if($recommendUser['weixin']==$user['weixin']){
                        return "您要绑定的推荐人你自己，且绑定只能一次，这将造成您后期无法获得返点，请慎重考虑！";
                    }
                    return "您要绑定的推荐人是：".$recommendUser['nickname'];
                }else{
                    return "该推荐码不存在";
                }
            }else{
                return "请先填写推荐码";
            }
        }
        throw new Exception("非法获取");
    }
}