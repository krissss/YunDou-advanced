<?php
/** @var $payRecords \common\models\Money[] */
/** @var $pages */

use yii\helpers\Url;
use common\functions\CommonFunctions;
use common\models\Money;

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
    <p>
        <strong>充值</strong>
        <a href="<?=Url::to(['account/index'])?>" class="btn btn-primary btn-sm pull-right margin-left-10">云豆记录</a>
        <a href="<?= Url::to(['account/invoice-apply']) ?>" class="btn btn-primary btn-sm pull-right">发票申请</a>
    </p>
    <table class="table table-striped">
        <tbody>
        <tr>
            <td>总计充值:<?=Money::findTotalPay($user['userId'])?>元</td>
            <td>总计充值获得:+<?=Money::findTotalPayBitcoin($user['userId'])?>颗云豆</td>
        </tr>
        </tbody>
    </table>
    <p><strong>详细记录</strong></p>
    <table class="table table-striped text-center no-margin-bottom">
        <thead>
            <tr>
                <th class="text-center">金额</th>
                <th class="text-center">云豆</th>
                <th class="text-center">日期</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($payRecords as $i=>$payRecord): ?>
            <tr>
                <td><?=$payRecord['money']?></td>
                <td>+<?=$payRecord['bitcoin']?></td>
                <td><?=$payRecord['createDate']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav class="pull-right pagination_footer">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </nav>
    <div class="clearfix"></div>

</div>
