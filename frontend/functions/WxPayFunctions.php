<?php
/** 微信支付方法 */
namespace frontend\functions;

require_once "./../functions/wxPayLibs/WxPay.Api.php";
require_once "./../functions/wxPay/WxPay.JsApiPay.php";

use Yii;
use yii\helpers\Url;
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
                $addBitcoin = intval($xmlArray['attach']);  //获取充值获得的云豆数
                Money::recordOne($user, $money, $addBitcoin, Money::TYPE_PAY, Money::FROM_WX);   //记录充值记录+返点+收入支出表变化+用户云豆数增加
                CommonFunctions::logger_wx("订单:".$transaction_id.",openId:".$openId.",userId:".$user['userId'].",支付".$money."元,获得".$addBitcoin."云豆,交易类型:".$xmlArray['trade_type'].",交易结束时间:".$xmlArray['time_end']);
                echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            }
        }else{
            CommonFunctions::logger_wx("错误消息:".$xmlArray['return_msg']);
            echo 'fail';
        }
        exit;
    }

    /** js客户端订单 */
    public function generateJsOrder($scheme){
        //获取用户openid
        $session = Yii::$app->session;
        $openId = $session->get('openId');
        $user = $session->get('user');
        if($user['weixin']!=$openId){
            echo ("用户标识不一致，无法完成订单");
            exit;
        }

        $input = $this->unifiedOrder($scheme['payMoney']);
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $input->SetAttach($scheme['getBitcoin']);   //传递获取到的云豆数
        /** @var $order array */
        $order = \WxPayApi::unifiedOrder($input);
        return $order;
    }

    /** 二维码订单 */
    public function generateQrOrder($scheme){
        $input = $this->unifiedOrder($scheme['payMoney']);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id("123456789");
        $input->SetAttach($scheme['getBitcoin']);   //传递获取到的云豆数
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