<?php
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
if(!empty($postStr)){
    \common\functions\CommonFunctions::logger_wx($postStr);
    \frontend\functions\WxPayFunctions::payNotify($postStr);
}