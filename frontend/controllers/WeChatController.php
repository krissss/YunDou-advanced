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
}