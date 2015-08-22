<?php
/** @var $payRecords \common\models\Pay[] */

use yii\helpers\Url;
use common\functions\CommonFunctions;

$this->title = "充值记录";

$user = Yii::$app->session->get('user');
$userIcon = isset($user['userIcon'])?$user['userIcon']:null;
$userIcon = CommonFunctions::createHttpImagePath($userIcon);
?>
<div class="account-header">
    <div class="avatar">
        <img src="<?=$userIcon?>" alt="head">
        <p><?=$user['nickname']?></p>
        <p>云豆余额：<strong><?=$user['bitcoin']?></strong></p>
    </div>
</div>
<hr>
<div class="container-fluid">
    <h4 class="col-xs-4">充值记录</h4>
    <a href="<?= Url::to(['account/index']) ?>" class="btn btn-primary pull-right margin-left-10">云豆记录</a>
    <a href="<?= Url::to(['account/invoice-apply']) ?>" class="btn btn-primary pull-right">发票申请</a>
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">金额</th>
                <th class="text-center">云豆</th>
                <th class="text-center">日期</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($payRecords as $i=>$payRecord): ?>
            <tr>
                <th scope="row"><?=$i+1?></th>
                <td><?=$payRecord['money']?></td>
                <td><?=$payRecord['bitcoin']?></td>
                <td><?=$payRecord['createDate']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
