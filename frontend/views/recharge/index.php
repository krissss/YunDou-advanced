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
        <div class="pic_box_3 order" onclick="pay(this)" data-no="<?=$i?>">
            <div class="bitcoin"><?=$scheme['getBitcoin']?><small>云豆</small></div>
            <div class="rmb">售价<?=$scheme['payMoney']?>元</div>
        </div>
    <?php endforeach; ?>
</div>
    <script type="text/javascript">
        //调用微信JS api 支付
        function jsApiCall(orderNo)
        {
            switch (orderNo) {
                <?php foreach($jsApiParameters as $i=>$jsApiParameter):?>
                case <?=$i?>:
                    WeixinJSBridge.invoke(
                        'getBrandWCPayRequest',
                        <?php echo $jsApiParameters[$i]; ?>,
                        function(res){
                            if(res.err_msg == "get_brand_wcpay_request:ok" ) {
                                window.location.href = "?r=account/recharge&type=over";
                            }
                        }
                    );
                    break;
                <?php endforeach;?>
            }
        }
        function pay($this){
            var orderNo = $this.getAttribute('data-no');
            if (typeof WeixinJSBridge == "undefined"){
                if( document.addEventListener ){
                    document.addEventListener('WeixinJSBridgeReady', jsApiCall(orderNo), false);
                }else if (document.attachEvent){
                    document.attachEvent('WeixinJSBridgeReady', jsApiCall(orderNo));
                    document.attachEvent('onWeixinJSBridgeReady', jsApiCall(orderNo));
                }
            }else{
                jsApiCall(orderNo);
            }
        }
    </script>

