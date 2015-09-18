<?php
/** 微信支付方法 */
namespace backend\functions;

require_once "./../functions/wxPayLibs/WxPay.Api.php";
require_once "./../functions/wxPay/WxPay.JsApiPay.php";

use Yii;
use yii\helpers\Url;
use common\models\Money;
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
                $msg = "订单:".$transaction_id."，已处理完，重复通知";
                Yii::info($msg,'wx');
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            } else {
                $cache->set($transaction_id,'ok',24*3600);  //缓存1天
                $money = $xmlArray['total_fee']/100;    //总金额（元）
                $attachArray = explode("|",$xmlArray['attach']);
                $userId = intval($attachArray[0]);  //获取充值用户
                $user = Users::findOne($userId);
                $addBitcoin = intval($attachArray[1]);  //获取充值获得的云豆数
                Money::recordOneForBig($user, $money, $addBitcoin, Money::FROM_WX);   //记录充值记录+返点+收入支出表变化+用户云豆数增加
                $msg = "订单:".$transaction_id.",首次记录,userId:".$user['userId'].",支付".$money."元,获得".$addBitcoin."云豆,交易类型:".$xmlArray['trade_type'].",交易结束时间:".$xmlArray['time_end'];
                Yii::info($msg,'wx');
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            }
        }else{
            $msg = "错误消息:".$xmlArray['return_msg'];
            Yii::info($msg,'wx');
            echo 'fail';
        }
        exit;
    }

    /** 二维码订单 */
    public function generateQrOrder($scheme){
        $user = Yii::$app->session->get('user');

        $input = $this->unifiedOrder($scheme['payMoney']);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");
        $input->SetAttach($user['userId']."|".$scheme['getBitcoin']);   //传递用户id和获取到的云豆数
        $order = \WxPayApi::unifiedOrder($input);
        $qrUrl = $order["code_url"];
        return $qrUrl;
    }

    /** 统一下单 */
    public function unifiedOrder($money){
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("云豆充值");
        $input->SetAttach("云豆充值");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis").rand(100,999));
        //totalFee是以分为单位的，正式情况下应该乘以100
        $totalFee = $money*100;
        $input->SetTotal_fee($totalFee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //$input->SetGoods_tag("test");
        $input->SetNotify_url(Url::base(true).'/notify.php');
        //$input->SetNotify_url(Url::to(['/we-chat/notify'],true));
        return $input;
    }
}