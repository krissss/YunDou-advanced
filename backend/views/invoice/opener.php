<?php
/* @var $this yii\web\View */
/* @var $models common\models\Invoice[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '发票开具';
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>

                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['invoice/opener'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/find','type'=>'money-more','content'=>'100'])?>">开票金额大于100</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/find','type'=>'money-more','content'=>'500'])?>">开票金额大于500</a>
                <a class="btn btn-default" href="<?=Url::to(['invoice/find','type'=>'money-more','content'=>'1000'])?>">开票金额大于1000</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['invoice/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="username">用户名称</option>
                        <option value="money">开票金额大于</option>
                        <option value="money">开票金额等于</option>
                        <option value="money">开票金额小于</option>
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
                <th class="text-align-center">用户昵称</th>
                <th class="text-align-center">申请开票金额</th>
                <th class="text-align-center">申请开票时间</th>
                <th class="text-align-center">描述</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">经手人</th>
                <th class="text-align-center">回复日期</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->invoiceId ?></td>
                    <td><?= $model->userId ?></td>
                    <td class="invoice_nickname_<?= $model->invoiceId ?>"><?= $model->users['nickname']?></td>
                    <td class="invoice_money_<?= $model->invoiceId ?>"><?= $model->money ?></td>
                    <td class="invoice_createDate_<?= $model->invoiceId ?>"><?= $model->createDate ?></td>
                    <td><?= $model->description ?></td>
                    <td class="invoice_state_<?= $model->invoiceId ?>"><?= $model->stateName  ?></td>
                    <td><?= $model->replyUser['nickname'] ?></td>
                    <td><?= $model->replyDate ?></td>
                    <td>
                        <button class="btn btn-xs btn-default edit_number"
                                data-toggle="modal" data-target="#edit_number" data-id="<?=$model->invoiceId?>">填写快递单号</button>
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

<div class="modal fade" id="edit_number" tabindex="-1" role="dialog" aria-labelledby="填写快递单号">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">填写快递单号</h4>
            </div>
            <?=Html::beginForm(['invoice/opener'], 'post', ['class' => 'form-horizontal']);?>
            <div class="modal-body">
                <input class="invoiceId" type="hidden" name="invoiceId" value="">
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">申请人</label>
                    <p class="col-sm-9 invoice_nickname"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">申请金额</label>
                    <p class="col-sm-9 invoice_money"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">申请时间</label>
                    <p class="col-sm-9 invoice_createDate"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">状态</label>
                    <p class="col-sm-9 invoice_state"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">快递单号</label>
                    <input type="text" name="orderNumber">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>