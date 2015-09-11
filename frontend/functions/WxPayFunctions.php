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
     * @throws \yii\base\Exception
     */
    public static function payNotify($xml){
        $cache = \Yii::$app->cache;
        $xmlObj = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        CommonFunctions::logger_wx($xmlObj);
        foreach($xmlObj as $a){
            CommonFunctions::logger_wx($a);
        }
        if($xmlObj->return_code == 'SUCCESS'){
            $transaction_id = $xmlObj->transaction_id;
            if('ok' == $cache->get($transaction_id)){
                CommonFunctions::logger_wx("订单:".$transaction_id."，已处理完，重复通知");
                return 'success';
            } else {
                CommonFunctions::logger_wx("订单:".$transaction_id."，首次记录");
                $cache->set($transaction_id,'ok',24*3600);  //缓存1天
                $openId = $xmlObj->openid;
                $user = Users::findByWeiXin($openId);   //获得付款用户
                $money = $xmlObj->total_fee / 100;    //总金额（元）
                $scheme = Scheme::findPayScheme();  //获取充值方案
                $proportion = intval($scheme['getBitcoin']) / intval($scheme['payMoney']);    //充值比例,1：X的X
                $addBitcoin = intval($money) * $proportion; //计算应得的云豆数
                Money::recordOne($user, $money, $addBitcoin, Money::TYPE_PAY, Money::FROM_WX);   //记录充值记录+返点+收入支出表变化+用户云豆数增加
                CommonFunctions::logger_wx("订单:".$transaction_id."，".$openId."pay￥".$money);
                return 'success';
            }
        }else{
            CommonFunctions::logger_wx("错误消息:".$xmlObj->return_msg);
        }
    }
}