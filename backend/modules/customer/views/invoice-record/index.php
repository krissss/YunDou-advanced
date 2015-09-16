<?php
/* @var $this yii\web\View */
/* @var $models common\models\Invoice[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Invoice;

$this->params['breadcrumbs'] = [
    '发票列表',
    $this->title
];
$user = Yii::$app->session->get('user');
?>
<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <div class="view">
                <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['invoice-record/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice-record/search','type'=>'state','content'=>Invoice::STATE_ING])?>">申请中发票</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice-record/search','type'=>'state','content'=>Invoice::STATE_REFUSE])?>">管理员拒绝</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice-record/search','type'=>'state','content'=>Invoice::STATE_PASS])?>">管理员允许</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice-record/search','type'=>'state','content'=>Invoice::STATE_OVER])?>">已完成配送</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['invoice-record/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="money-more">开票金额大于</option>
                        <option value="money-equal">开票金额等于</option>
                        <option value="money-less">开票金额小于</option>
                        <option value="orderNumber">快递单号</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-bordered table-striped margin-bottom-20">
            <tr>
                <td><strong>总计已完成申请开票金额：</strong><?=Invoice::findTotalInvoice($user['userId'])?>(元)</td>
            </tr>
        </table>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">申请开票金额</th>
                <th class="text-align-center">申请开票时间</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">详细地址</th>
                <th class="text-align-center">管理员回复内容</th>
                <th class="text-align-center">快递单号</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr class="invoice_<?= $model->invoiceId ?>">
                    <td><?= $model->invoiceId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->user['nickname']?></td>
                    <td><?= $model->money ?></td>
                    <td><?= $model->createDate ?></td>
                    <td><?= $model->stateName  ?></td>
                    <td><?= $model->address ?></td>
                    <td><?= $model->replyContent ?></td>
                    <td><?= $model->orderNumber ?></td>
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

