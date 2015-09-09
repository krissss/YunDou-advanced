<?php
/* @var $this yii\web\View */
/* @var $models common\models\withdraw[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Withdraw;

$this->title =  '提现管理';
$this->params['breadcrumbs'] = [
    '合作管理',
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
                <a class="btn btn-default" href="<?=Url::to(['withdraw/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'money-more','content'=>'100'])?>">提现金额大于100</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'money-more','content'=>'500'])?>">提现金额大于500</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'money-more','content'=>'1000'])?>">提现金额大于1000</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'apply','content'=>Withdraw::STATE_APPLYING])?>">申请中记录</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'agree','content'=>Withdraw::STATE_PASS])?>">已通过记录</a>
                <a class="btn btn-default" href="<?=Url::to(['withdraw/search','view'=>'index','type'=>'refuse','content'=>Withdraw::STATE_REFUSE])?>">驳回记录</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['withdraw/search','view'=>'index'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="nickname">用户名称</option>
                        <option value="money-more">提现金额大于</option>
                        <option value="money-equal">提现金额等于</option>
                        <option value="money-less">提现金额小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-bordered table-striped margin-bottom-20">
            <tr>
                <td><strong>总计提现金额：</strong><?=Withdraw::findTotalUsersMoney()?>(元)</td>
            </tr>
        </table>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">提现金额(元)</th>
                <th class="text-align-center">申请提现时间</th>
                <th class="text-align-center">发票金额</th>
                <th class="text-align-center">发票单号</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">审核人</th>
                <th class="text-align-center">回复内容</th>
                <th class="text-align-center">回复时间</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr><td><?= $model->withdrawId ?></td>
                    <td><?= $model->user['nickname'] ?></td>
                    <td><?= $model->money ?></td>
                    <td><?= $model->createDate ?></td>
                    <td><?= $model->invoiceMoney ?></td>
                    <td><?= $model->invoiceNo ?></td>
                    <td><?= $model->stateName ?></td>
                    <td><?= $model->replyUser['nickname'] ?></td>
                    <td><?= $model->replyContent ?></td>
                    <td><?= $model->replyDate ?></td>
                    <td>
                        <?php if($model->state==Withdraw::STATE_APPLYING):?>
                            <button class="btn btn-xs btn-default withdraw_btn" data-id="<?=$model->withdrawId?>">处理</button>
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


