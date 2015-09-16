<?php

namespace backend\modules\associate\controllers;

use backend\filters\FrozenFilter;
use backend\modules\associate\models\forms\ApplyMoneyForm;
use Yii;
use yii\web\Controller;
use common\functions\CommonFunctions;

class ApplyController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => FrozenFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $applyMoneyForm = new ApplyMoneyForm();
        $request = Yii::$app->request;
        if($request->isPost){
            if($applyMoneyForm->load($request->post()) && $applyMoneyForm->validate()){
                if($applyMoneyForm->record()){
                    CommonFunctions::createAlertMessage("申请提交成功","success");
                }else{
                    CommonFunctions::createAlertMessage("申请提交失败，原因是已经存在正在申请中的记录","error");
                }
            }else{
                CommonFunctions::createAlertMessage("填写的信息有错误","error");
            }
        }else{
            CommonFunctions::createAlertMessage("提现申请提交后请及时提交对应金额的发票<b>（开票单位：南京云宝网络有限公司，开票项目：服务费）</b>，
            系统会提交相关人员审核，审核后将会打款至您提交的银行账户。提现金额最低100元。","info");
        }
        return $this->render('index', [
            'applyMoneyForm' => $applyMoneyForm,
        ]);
    }
}