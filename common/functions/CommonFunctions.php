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

    /**
     * 加密密码
     * @param $str
     * @return string
     */
    public static function encrypt($str){
        return md5($str);
    }

    public static function decrypt($str){

    }

    /**
     * 创建验证码
     * @return int|string
     */
    public static function createRecommendCode(){
        $code = time();
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i = 0; $i < 5; $i ++) {
            $code .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
        }
        return $code;
    }

    /**
     * 创建http图片路径，仅适用与frontend
     * @param $imagePath
     * @return string
     */
    public static function createHttpImagePath($imagePath){
        if($imagePath == null){
            return "http://".$_SERVER['SERVER_NAME']."/frontend/web/images/default.jpg";
        }
        if(strpos($imagePath,"http")==0){
            return $imagePath;
        }else{
            return "http://".$_SERVER['SERVER_NAME']."/frontend/web/images/".$imagePath;
        }
    }
}