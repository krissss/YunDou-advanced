<?php
/** @var $order */
?>
<div class="text-center">
    <img alt="微信二维码支付" src="./qrcode.php?data=<?php echo urlencode($order);?>" style="width:150px;height:150px;"/>
</div>
<script type="text/javascript">
    alert("请使用微信扫描下方二维码完成支付");
</script>