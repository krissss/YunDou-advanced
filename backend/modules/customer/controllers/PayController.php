<?php
/** 自主充值 */
namespace backend\modules\customer\controllers;

use backend\filters\FrozenFilter;
use backend\functions\WxPayFunctions;
use common\models\Scheme;
use Yii;
use yii\web\Controller;

class PayController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => FrozenFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $schemes = Scheme::findPayScheme();  //获取充值方案
        $orders=[];
        $wxPayFunctions = new WxPayFunctions();
        foreach($schemes as $scheme){
            array_push($orders,$wxPayFunctions->generateQrOrder($scheme));
        }
        return $this->render('index',[
            'schemes' => $schemes,
            'orders' => $orders
        ]);
    }
}