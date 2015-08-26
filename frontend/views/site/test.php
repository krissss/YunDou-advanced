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

//echo \common\functions\CommonFunctions::createCommonRecommendCode();

//echo Url::base(true);
//\common\models\Users::addBitcoin(5,10000);

//echo \common\models\ExamScore::findTotalRank();
//echo \common\models\ExamScore::findRank(15,150);

print_r( \common\models\Users::findRecommendUser(null));