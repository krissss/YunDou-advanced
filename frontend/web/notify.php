<?php
require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');
$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:"";
if(!empty($postStr)){
    \frontend\functions\WxPayFunctions::payNotify($postStr);
}else{
    \common\functions\CommonFunctions::logger_wx("收到空的通知");
}
