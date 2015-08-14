<?php

namespace common\functions;

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

    public static function createRecommendCode(){
        $code = time();
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i = 0; $i < 5; $i ++) {
            $code .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
        }
        return $code;
    }
}