<?php
/** 自主充值 */
namespace backend\modules\customer\controllers;

use backend\modules\customer\models\forms\PayForm;
use common\functions\CommonFunctions;
use common\models\Scheme;
use common\models\Users;
use Yii;
use yii\web\Controller;

class PayController extends Controller
{
    public function actionIndex(){
        $payForm = new PayForm();
        $request = Yii::$app->request;
        if($request->isPost){
            $payForm->money = $request->post('money');
            if($payForm->validate()){
                $order = $payForm->generateQrOrder();
                return $this->render('qr-order',[
                    'order' => $order
                ]);
            }else{
                CommonFunctions::createAlertMessage("填写的表单信息有误，请认真查看后再提交","error");
            }
        }
        $scheme = Scheme::findPayScheme();  //获取充值方案
        $proportion = intval($scheme['getBitcoin'])/intval($scheme['payMoney']);    //充值比例,1：X的X
        $msg = '当前云豆充值比例（人民币元：云豆颗）为1:'.$proportion;
        $user = Yii::$app->session->get('user');
        $leftBitcoin = Users::findBitcoin($user['userId']); //剩余的云豆
        CommonFunctions::createAlertMessage($msg,"info");
        return $this->render('index',[
            'payForm' => $payForm,
            'leftBitcoin' => $leftBitcoin,
            'proportion' => $proportion
        ]);
    }
}