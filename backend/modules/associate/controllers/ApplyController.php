<?php

namespace backend\modules\associate\controllers;

use backend\modules\associate\models\forms\ApplyMoneyForm;
use Yii;
use yii\web\Controller;
use common\functions\CommonFunctions;



class ApplyController extends Controller
{
    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $applyMoneyForm = new ApplyMoneyForm();
        $request = Yii::$app->request;
        if($request->isPost){
            $applyMoneyForm->money = $request->post('money');
            if($applyMoneyForm->validate()){
                if($applyMoneyForm->record()){
                    CommonFunctions::createAlertMessage("申请提交成功","success");
                }else{
                    CommonFunctions::createAlertMessage("申请提交失败，原因是已经存在正在申请中的记录","error");
                }
            }else{
                CommonFunctions::createAlertMessage("填写的信息有错误","error");
            }
        }else{
            CommonFunctions::createAlertMessage("提现金额将由管理员审核后打到您的XXX账号，提现金额50元起可提现，不得超过云豆余额","info");
        }
        return $this->render('index', [
            'user' =>$user,
            'applyMoneyForm' => $applyMoneyForm
        ]);
    }
}