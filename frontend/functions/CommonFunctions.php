<?php

namespace frontend\functions;


class CommonFunctions
{
    public static function createRecommendCode(){
        $code = time();
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
        for($i = 0; $i < 5; $i ++) {
            $code .= $pattern {mt_rand ( 0, 61 )}; //生成php随机数
        }
        return $code;
    }

}