<?php
/** @var $schemes \common\models\Scheme[] */
/** @var $orders */

use common\functions\CommonFunctions;
use common\models\Users;

$this->title = "云豆充值";
$user = Yii::$app->session->get('user');
$userIcon = isset($user['userIcon'])?$user['userIcon']:null;
$userIcon = CommonFunctions::createHttpImagePath($userIcon);

$tools = new JsApiPay();
/** @var $jsApiParameters Array //JSON */
$jsApiParameters = [];
foreach($orders as $order){
    array_push($jsApiParameters,$tools->GetJsApiParameters($order));
}
?>
<div class="account-header">
    <div class="avatar">
        <img src="<?=$userIcon?>" alt="head">
        <p><?=$user['nickname']?></p>
        <p>云豆余额：<strong><?=Users::findBitcoin($user['userId'])?></strong></p>
    </div>
</div>
<hr>
<div class="container-fluid">
<?=\common\widgets\AlertWidget::widget()?>
<div class="container-fluid">
    <?php foreach($schemes as $i=>$scheme): ?>
        <div class="pic_box_3 order" onclick="pay(this)" data-param='<?=$jsApiParameters[$i]?>'>
            <div class="bitcoin"><?=$scheme['getBitcoin']?><small>云豆</small></div>
            <div class="rmb">售价<?=$scheme['payMoney']?>元</div>
        </div>
    <?php endforeach; ?>
</div>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall($jsApiParameters)
        {
            $jsApiParameters = JSON.parse($jsApiParameters);
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',
                $jsApiParameters,
                function(res){
                    if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                        window.location.href = "?r=account/recharge&type=over";
                    }
                }
            );
        }
        function pay($this){
            var $jsApiParameters = $this.getAttribute('data-param');
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall($jsApiParameters), false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall($jsApiParameters));
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall($jsApiParameters));
                }
            }else{
                jsApiCall($jsApiParameters);
            }
        }
    </script>

