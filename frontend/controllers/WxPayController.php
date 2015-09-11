<?php
/** 微信支付相关的控制器 */
namespace frontend\controllers;

use frontend\models\forms\RechargeForm;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

class WxPayController extends Controller
{
    public function actionRequestOrder($type){
        $request = Yii::$app->request;
        if($request->isPost) {   //调用微信支付页面
            $rechargeForm = new RechargeForm();
            $rechargeForm->money = $request->post('money');
            if ($rechargeForm->validate()) {
                if ($type == 'js') {  //js客户端
                    $order = $rechargeForm->generateJsOrder();
                    return $this->renderAjax('js-order', [
                        'order' => $order
                    ]);
                } elseif ($type == 'qr') { //二维码
                    $order = $rechargeForm->generateQrOrder();
                    return $this->renderAjax('qr-order', [
                        'order' => $order
                    ]);
                }
            } else {
                return "充值表单有错误";
            }
        }
        throw new Exception("非法请求");
    }
}