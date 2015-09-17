<?php
/** @var $schemes \common\models\Scheme[] */
/** @var $orders */

$this->title = "自主充值";

$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <?=\common\widgets\AlertWidget::widget()?>
        <h4>自主充值</h4>
        <div class="form-title"></div>
        <?php foreach($schemes as $i=>$scheme): ?>
            <div class="col-md-2 col-sm-4 col-xs-6">
                <div class="box_click order" onclick="pay(this)" data-param='<?=$orders[$i]?>'>
                    <div class="bitcoin"><?=$scheme['getBitcoin']?><small>云豆</small></div>
                    <div class="rmb">售价<?=$scheme['payMoney']?>元</div>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="clearfix"></div>
        <div id="qr_box" class="text-center" style="display: none">
            <p><strong>用微信扫一扫支付</strong></p>
            <img id="qr" alt="微信二维码支付" src="" style="width:150px;height:150px;"/>
        </div>
    </div>
</div>
<script type="text/javascript">
    //调用微信JS api 支付
    function pay($this){
        var data = $this.getAttribute('data-param');
        document.getElementById('qr').src = "./qrcode.php?data="+data;
        document.getElementById('qr_box').style.display = "block";
    }
</script>