<?php
/* @var $this yii\web\View */
/* @var $models common\models\Withdraw[] */
/* @var $pages */

use common\models\Withdraw;
use yii\helpers\Url;
use yii\helpers\Html;
use common\models\Users;

$this->title = '提现记录';
$this->params['breadcrumbs'] = [
    $this->title
];

$user = Yii::$app->session->get('user');
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['money/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'state','content'=>Withdraw::STATE_PASS])?>">管理员允许</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'state','content'=>Withdraw::STATE_REFUSE])?>">管理员拒绝</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['money/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="withdraw-more">提现金额大于</option>
                        <option value="withdraw-equal">提现金额等于</option>
                        <option value="withdraw-less">提现金额小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-bordered table-striped margin-bottom-20">
            <tr>
                <td><strong>总计提现金额：</strong><?=Withdraw::findTotalMoney($user['userId'])?>(元)</td>
                <td><strong>总计消耗云豆：</strong><?=Withdraw::findTotalBitcoin($user['userId'])?>(颗)</td>
                <td><strong>剩余云豆：</strong><?=Users::findBitcoin($user['userId'])?>(颗)</td>
            </tr>
        </table>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">提现金额(元)</th>
                <th class="text-align-center">消耗云豆(颗)
                </th><th class="text-align-center">申请提现时间</th>
                <th class="text-align-center">发票金额</th>
                <th class="text-align-center">发票单号</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">审核人</th>
                <th class="text-align-center">回复内容</th>
                <th class="text-align-center">回复时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->withdrawId ?></td>
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
