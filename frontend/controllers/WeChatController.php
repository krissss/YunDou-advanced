<?php

namespace frontend\controllers;

use common\functions\CommonFunctions;
use frontend\functions\WeChatCallBack;
use yii\base\Controller;

class WeChatController extends Controller
{
    /** 微信验证和微信接收消息的地址 */
    public function actionIndex(){
        $wechat = new WeChatCallBack();
        //$wechat->valid();
        $wechat->responseMsg();
    }

    /** 微信支付后接受通知的地址  */
    public function actionNotify(){
        $postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:"";
        if(!empty($postStr)) {
            \common\functions\CommonFunctions::logger_wx($postStr);
            \frontend\functions\WxPayFunctions::payNotify($postStr);
        }else{
            CommonFunctions::logger_wx("接收到通知");
        }
    }
}