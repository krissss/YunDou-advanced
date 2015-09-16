<?php
$this->title = "云豆充值跳转页面";
?>
<div class="load-container loading">
    <div class="loader">Loading...</div>
    <p>正在跳转充值页面，请稍等。。。</p>
</div>
<script type="text/javascript">
    var wechatInfo = navigator.userAgent.match(/MicroMessenger\/([\d\.]+)/i) ;
    var mobileInfo = navigator.userAgent.match(/Mobile/i);
    if( !wechatInfo || !mobileInfo || wechatInfo[1] < "5.0") {  //使用二维码扫码支付
        window.location.href="?r=recharge/qr-order";
    }else{  //调用微信支付
        window.location.href="?r=recharge/js-order";
    }
</script>
