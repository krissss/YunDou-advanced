<?php

use common\functions\CommonFunctions;
use common\models\Users;

$this->title = "云豆充值成功";
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
</div>