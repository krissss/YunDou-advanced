<?php
/**
 * Created by PhpStorm.
 * User: a8652
 * Date: 2015/7/31
 * Time: 13:14
 */

namespace frontend\controllers;

use frontend\functions\WeChatCallBack;

class WeChatController
{
    public function actionIndex(){
        $wechat = new WeChatCallBack();
        //$wechat->valid();
        $wechat->responseMsg();
    }
}