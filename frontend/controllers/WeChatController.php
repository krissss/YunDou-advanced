<?php

namespace frontend\controllers;

use frontend\functions\WeChatCallBack;
use yii\base\Controller;

class WeChatController extends Controller
{
    public function actionIndex(){
        $wechat = new WeChatCallBack();
        //$wechat->valid();
        $wechat->responseMsg();
    }
}