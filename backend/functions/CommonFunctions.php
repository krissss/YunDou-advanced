<?php

namespace backend\functions;

use Yii;

class CommonFunctions
{
    /**
     * 配合AlertWidget使用，显示消息弹出框
     * @param $message
     * @param $message_type //可填写'success','warning','error','info'
     */
    public static function createAlertMessage($message, $message_type){
        Yii::$app->session->setFlash('message',$message);
        Yii::$app->session->setFlash('message_type',$message_type);
    }

    public static function encrypt($str){
        return md5($str);
    }

    public static function decrypt($str){

    }
}