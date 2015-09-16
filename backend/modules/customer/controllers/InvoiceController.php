<?php
/** 发票申请 */
namespace backend\modules\customer\controllers;

use backend\modules\customer\models\forms\ApplyInvoiceForm;
use common\functions\CommonFunctions;
use Yii;
use yii\web\Controller;

class InvoiceController extends Controller
{
    public function actionIndex()
    {
        $user = Yii::$app->session->get('user');
        $request = Yii::$app->request;
        $applyInvoiceForm = new ApplyInvoiceForm();
        if ($request->isPost) {
            if ($applyInvoiceForm->load($request->post()) && $applyInvoiceForm->validate()) {
                if ($applyInvoiceForm->record()) {
                    CommonFunctions::createAlertMessage("申请提交成功", 'success');
                } else {
                    CommonFunctions::createAlertMessage("申请提交失败，原因是已经存在正在申请中的记录", "error");
                }
            } else {
                CommonFunctions::createAlertMessage("申请提交失败，填写信息有误", 'error');
            }
        }
        return $this->render('index', [
            'user' => $user,
            'applyInvoiceForm' => $applyInvoiceForm
        ]);
    }
}