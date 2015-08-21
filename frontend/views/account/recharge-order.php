<?php
/** @var $order */

$tools = new JsApiPay();
/** @var $jsApiParameters String //JSON */
$jsApiParameters = $tools->GetJsApiParameters($order);
$jsApiParameters = json_decode($jsApiParameters);

//获取共享收货地址js函数参数
/** @var $editAddress String //JSON */
$editAddress = $tools->GetEditAddressParameters();

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */

?>
<?php
use yii\helpers\Url;
use frontend\functions\WeiXinFunctions;
$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: true,
        appId: '<?=WeiXinFunctions::getAppId()?>',
        timestamp: <?=$timestamp?>,
        nonceStr: 'yundou-js',
        signature: '<?=WeiXinFunctions::generateJsSignature($currentUrl,$timestamp)?>',
        jsApiList: [
            'onMenuShareTimeline',    //分享到朋友圈
            'chooseWXPay',    //分享到朋友圈
        ]
    });
    wx.ready(function(){
        wx.chooseWXPay({
            timestamp: 0, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写。但最新版的支付后台生成签名使用的timeStamp字段名需大写其中的S字符
            nonceStr: '<?=$jsApiParameters->nonceStr?>', // 支付签名随机串，不长于 32 位
            package: '<?=$jsApiParameters->package?>', // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
            signType: '<?=$jsApiParameters->signType?>', // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
            paySign: '<?=$jsApiParameters->paySign?>', // 支付签名
            success: function (res) {
                // 支付成功后的回调函数
                alert(res.err_msg);
            }
        });
    });
</script>
<!--<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php /*echo $jsApiParameters; */?>,
            function(res){
                if(res.err_msg == "get_brand_wcpay_request：ok" ) {

                }
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }

</script>-->
<?php
echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//打印输出数组信息
foreach($order as $key=>$value){
    echo "<font color='#00ff55;'>$key</font> : $value <br/>";
}
?>
<button onclick="callpay()">确认</button>
