<?php
/** @var $schemes \common\models\Scheme[] */
/** @var $orders */

use common\functions\CommonFunctions;
use common\models\Users;

$this->title = "云豆二维码充值";
$user = Yii::$app->session->get('user');
$userIcon = isset($user['userIcon'])?$user['userIcon']:null;
$userIcon = CommonFunctions::createHttpImagePath($userIcon);

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
    <?php foreach($schemes as $i=>$scheme): ?>
        <div class="pic_box_3 order" onclick="pay(this)" data-param='<?=$orders[$i]?>'>
            <div class="bitcoin"><?=$scheme['getBitcoin']?><small>云豆</small></div>
            <div class="rmb">售价<?=$scheme['payMoney']?>元</div>
        </div>
    <?php endforeach; ?>
    <div id="qr_box" class="text-center" style="display: none">
        <p><strong>扫一扫支付</strong></p>
        <img id="qr" alt="微信二维码支付" src="" style="width:150px;height:150px;"/>
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

