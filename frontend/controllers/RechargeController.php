<?php
/** 充值控制器 */
namespace frontend\controllers;

use common\functions\CommonFunctions;
use common\models\Scheme;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\filters\RegisterFilter;
use frontend\functions\WxPayFunctions;
use Yii;
use yii\web\Controller;

class RechargeController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],[
                'class' => RegisterFilter::className(),
            ],
        ];
    }

    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $schemes = Scheme::findPayScheme();  //获取充值方案
        $recommendUser = Users::findRecommendUser($user['recommendUserID']);
        if($recommendUser && $recommendUser['role']!=Users::ROLE_BIG){ //存在推荐用户且不为大客户则需要有返点消息提示，否则没有提示
            $rebateScheme = Scheme::findRebateScheme($recommendUser['role']);  //获取返点方案
        }else{
            $rebateScheme = "";
        }
        if($rebateScheme){
            $msg = '充值金额大于'.$rebateScheme['payMoney'].'元时可以获得'.($rebateScheme['rebateSelf']*100).'%返点哦~。';
            CommonFunctions::createAlertMessage($msg,"info");
        }
        $orders=[];
        $wxPayFunctions = new WxPayFunctions();
        foreach($schemes as $scheme){
            array_push($orders,$wxPayFunctions->generateJsOrder($scheme['payMoney']));
        }
        return $this->render('index',[
            'schemes' => $schemes,
            'orders' => $orders
        ]);
    }
}