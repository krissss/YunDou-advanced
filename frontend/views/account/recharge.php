<?php
/** @var $rechargeForm \frontend\models\forms\RechargeForm */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

//①、获取用户openid
$session = Yii::$app->session;
$openId = $session->get('openId');
$user = $session->get('user');
if($user['weixin']!=$openId){
    echo ("用户标识不一致，无法完成订单");
    exit;
}
$tools = new \frontend\wxPay\JsApiPay();

//②、统一下单
$input = new \frontend\wxPay\WxPayUnifiedOrder();
$input->SetBody("test");
$input->SetAttach("test");
$input->SetOut_trade_no(\frontend\wxPay\WxPayConfig::MCHID.date("YmdHis"));
$input->SetTotal_fee("1");
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("test");
$input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
/** @var $order array */
$order = \frontend\wxPay\WxPayApi::unifiedOrder($input);
echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//打印输出数组信息
foreach($order as $key=>$value){
    echo "<font color='#00ff55;'>$key</font> : $value <br/>";
}
/** @var $jsApiParameters String //JSON */
$jsApiParameters = $tools->GetJsApiParameters($order);

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
<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
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
    //获取共享地址
    function editAddress()
    {
        WeixinJSBridge.invoke(
            'editAddress',
            <?php echo $editAddress; ?>,
            function(res){
                var value1 = res.proviceFirstStageName;
                var value2 = res.addressCitySecondStageName;
                var value3 = res.addressCountiesThirdStageName;
                var value4 = res.addressDetailInfo;
                var tel = res.telNumber;

                alert(value1 + value2 + value3 + value4 + ":" + tel);
            }
        );
    }

    window.onload = function(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', editAddress, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', editAddress);
                document.attachEvent('onWeixinJSBridgeReady', editAddress);
            }
        }else{
            editAddress();
        }
    };

</script>
<div class="alert alert-info" role="alert">支付接口申请中</div>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'id' => 'account-register',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-9 no-padding-left'>{input}</div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-3 control-label'],
        ],
    ]) ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">您的账户余额</label>
        <div class="col-xs-9 no-padding-left">
            <p class="form-control-static">1000</p>
        </div>
    </div>
    <?= $form->field($rechargeForm, 'money') ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">您能够获得的云豆数</label>
        <div class="col-xs-9 no-padding-left">
            <p class="form-control-static">10000</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="submit" class="btn btn-primary">微信支付</button>
            <button type="submit" class="btn btn-primary">支付宝支付</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
