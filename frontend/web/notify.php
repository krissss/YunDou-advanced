<?php
$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
if(!empty($postStr)){
    \frontend\functions\WxPayFunctions::payNotify($postStr);
}