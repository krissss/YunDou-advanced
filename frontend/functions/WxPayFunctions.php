<?php
/** 微信支付方法 */
namespace frontend\functions;

use common\functions\CommonFunctions;
use common\models\Money;
use common\models\Scheme;
use common\models\Users;

class WxPayFunctions
{
    /**
     * 微信支付成功后的通知处理
     * @param $xml
     * @return string
     * @throws \yii\base\Exception
     */
    public static function payNotify($xml){
        $cache = \Yii::$app->cache;
        //转xml为array
        $xmlArray = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        if($xmlArray['return_code'] == 'SUCCESS'){
            $transaction_id = $xmlArray['transaction_id'];
            if('ok' == $cache->get($transaction_id)){
                CommonFunctions::logger_wx("订单:".$transaction_id."，已处理完，重复通知");
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            } else {
                CommonFunctions::logger_wx("订单:".$transaction_id."，首次记录");
                $cache->set($transaction_id,'ok',24*3600);  //缓存1天
                $openId = $xmlArray['openid'];
                $user = Users::findByWeiXin($openId);   //获得付款用户
                $money = $xmlArray['total_fee']/100;    //总金额（元）
                $scheme = Scheme::findPayScheme();  //获取充值方案
                $proportion = intval($scheme['getBitcoin']) / intval($scheme['payMoney']);    //充值比例,1：X的X
                $addBitcoin = intval($money) * $proportion; //计算应得的云豆数
                Money::recordOne($user, $money, $addBitcoin, Money::TYPE_PAY, Money::FROM_WX);   //记录充值记录+返点+收入支出表变化+用户云豆数增加
                CommonFunctions::logger_wx("订单:".$transaction_id.",openId:".$openId.",userId:".$user['userId'].",支付".$money."元,交易结束时间:".$xmlArray['time_end']);
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            }
        }else{
            CommonFunctions::logger_wx("错误消息:".$xmlArray['return_msg']);
            echo 'fail';
        }
        exit;
    }
}