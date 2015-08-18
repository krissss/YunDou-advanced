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

    /**
     * 替换[simg]为图片，前台使用
     * @param $preStr   //需要替换的字符串
     * @param $pictures //图片数组
     * @param $pictureIndex //图片数组下标
     * @return String
     */
    public static function replaceSmallImage($preStr,$pictures,&$pictureIndex){
        $imagePath = "./images/";
        $imgCount = substr_count($preStr,'[simg]'); //计算数量
        $finalStr = $preStr;  //最终构成的字符串
        for($i=0;$i<$imgCount;$i++){    //逐个替换[img]为对应的图片
            $finalStr = preg_replace('/\[simg\]/','<img class="inner_img_small" src="'.$imagePath.$pictures[$pictureIndex++].'">',$finalStr,1);
        }
        return $finalStr;
    }

    /**
     * 替换[bimg]为图片，前台使用
     * @param $preStr   //需要替换的字符串
     * @param $pictures //图片数组
     * @param $pictureIndex //图片数组下标
     * @return String
     */
    public static function replaceBigImage($preStr,$pictures,&$pictureIndex){
        $imagePath = "./images/";
        $imgCount = substr_count($preStr,'[bimg]'); //计算数量
        $finalStr = $preStr;  //最终构成的字符串
        for($i=0;$i<$imgCount;$i++){    //逐个替换[img]为对应的图片
            $finalStr = preg_replace('/\[bimg\]/','<div class="inner_img_big"><img src="'.$imagePath.$pictures[$pictureIndex++].'"></div>',$finalStr,1);
        }
        return $finalStr;
    }
}