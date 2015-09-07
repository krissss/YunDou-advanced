<?php
/* @var $this yii\web\View */
/* @var $models common\models\IncomeConsume[] */
/* @var $pages */

$this->title = '提现记录';
?>

<div class="widget flat">
    <div class="widget-body">
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">提现金额(元)</th>
                <th class="text-align-center">消耗云豆(颗)
                </th><th class="text-align-center">申请提现时间</th>
                <th class="text-align-center">发票金额</th>
                <th class="text-align-center">发票单号</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">经手人</th>
                <th class="text-align-center">回复内容</th>
                <th class="text-align-center">回复时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->user['nickname'] ?></td>
                    <td>+<?= $model->money ?></td>
                    <td>-<?= $model->bitcoin?></td>
                    <td><?= $model->createDate ?></td>
                    <td><?= $model->invoiceMoney ?></td>
                    <td><?= $model->invoiceNo ?></td>
                    <td><?= $model->stateName ?></td>
                    <td><?= $model->replyUser['nickname'] ?></td>
                    <td><?= $model->replyContent ?></td>
                    <td><?= $model->replyDate ?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <nav class="pull-right pagination_footer">
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]);?>
        </nav>
        <div class="clearfix"></div>
    </div>
</div>
