<?php
/* @var $this yii\web\View */
/* @var $models common\models\Invoice[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Invoice;

$this->title =  '发票申请';
$this->params['breadcrumbs'] = [
    '发票管理',
    $this->title
];
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <div class="view">
                <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['invoice/apply'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/search','view'=>'apply','type'=>'money-more','content'=>'100'])?>">开票金额大于100</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/search','view'=>'apply','type'=>'money-more','content'=>'500'])?>">开票金额大于500</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/search','view'=>'apply','type'=>'money-more','content'=>'1000'])?>">开票金额大于1000</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['invoice/search','view'=>'apply'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="nickname">用户名称</option>
                        <option value="money-more">开票金额大于</option>
                        <option value="money-equal">开票金额等于</option>
                        <option value="money-less">开票金额小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">申请开票金额</th>
                <th class="text-align-center">申请开票时间</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">详细地址</th>
                <th class="text-align-center">备注</th>
                <th class="text-align-center">操作</th>
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
                    <td><?= $model->description ?></td>
                    <td>
                        <?php if($model->state==Invoice::STATE_ING):?>
                            <button class="btn btn-xs btn-default invoice_btn" data-id="<?=$model->invoiceId?>">处理</button>
                        <?php else:?>已处理
                        <?php endif;?>
                    </td>
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

