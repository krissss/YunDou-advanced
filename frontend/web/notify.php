<?php
include_once("../../common/functions/CommonFunctions.php");
$postStr = isset($GLOBALS["HTTP_RAW_POST_DATA"])?$GLOBALS["HTTP_RAW_POST_DATA"]:"";
if(!empty($postStr)){
    include_once("../functions/WxPayFunctions.php");
    \common\functions\CommonFunctions::logger_wx($postStr);
    \frontend\functions\WxPayFunctions::payNotify($postStr);
}else{
    \common\functions\CommonFunctions::logger_wx("收到空的通知");
}