<?php
/** @var $order */

$this->title = "二维码支付";

$this->params['breadcrumbs'] = [
    [
        'label' => '云豆充值',
        'url' => ['pay/index'],
    ],
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <div class="text-center">
            <h3>请使用微信客户端扫描以下二维码完成支付</h3>
            <img alt="微信二维码支付" src="./qrcode.php?data=<?php echo urlencode($order);?>" style="width:300px;height:300px;"/>
        </div>
    </div>
</div>