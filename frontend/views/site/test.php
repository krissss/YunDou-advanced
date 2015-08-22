<?php
/**
 * 测试用页面
 */
use yii\helpers\Url;
use yii\helpers\Html;
//echo \frontend\functions\WeiXinFunctions::createMenu();
//::remember([Url::current([],true)],"openFilter");
//echo Url::current([],true);
//echo Url::to(['practice/normal','type'=>'continue','openId'=>"123"],true);
//$a = new \frontend\functions\WeChatCallBack();
//$a->ZIXUN_VIEW_Response("ow-bOvjH7CpKQtxsvjJuRmg6-g-k",35);

//echo \frontend\functions\CommonFunctions::createRecommendCode();

//echo \frontend\functions\DateFunctions::getCurrentDate();
//echo \common\models\PracticeRecord::findByUser(5);

//echo urldecode(Url::current([],true));

echo \common\models\Scheme::checkPayScheme(\common\models\Scheme::USAGE_PAY,"2015-08-22 00:00:00","2015-08-23 00:00:00");
