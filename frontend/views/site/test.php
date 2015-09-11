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

//print_r( \common\models\Users::findRecommendUser(null));
//\common\functions\CommonFunctions::logger_wx("真好");
//echo Url::to(['/we-chat/notify'],true);

/*$xml= '<xml><appid><![CDATA[wxcf0cd66d7cdf0708]]></appid>
<bank_type><![CDATA[CFT]]></bank_type>
<cash_fee><![CDATA[1]]></cash_fee>
<fee_type><![CDATA[CNY]]></fee_type>
<is_subscribe><![CDATA[Y]]></is_subscribe>
<mch_id><![CDATA[1253356301]]></mch_id>
<nonce_str><![CDATA[hp1m50dghqa138615bu5dkt5jd0gapww]]></nonce_str>
<openid><![CDATA[ow-bOvjH7CpKQtxsvjJuRmg6-g-k]]></openid>
<out_trade_no><![CDATA[125335630120150910140155]]></out_trade_no>
<result_code><![CDATA[SUCCESS]]></result_code>
<return_code><![CDATA[SUCCESS]]></return_code>
<sign><![CDATA[B318D8A29DF8533BBBADD8ECA9F2B2E8]]></sign>
<time_end><![CDATA[20150910140204]]></time_end>
<total_fee>1</total_fee>
<trade_type><![CDATA[JSAPI]]></trade_type>
<transaction_id><![CDATA[1000130168201509100836975983]]></transaction_id>
</xml>';
\frontend\functions\WxPayFunctions::payNotify($xml);*/

/*print_r(\common\models\Users::findByWeiXin("ow-bOvjH7CpKQtxsvjJuRmg6-g-k"));*/
?>
<a href="<?=Url::base(true).'/notify.php'?>">asd</a>
