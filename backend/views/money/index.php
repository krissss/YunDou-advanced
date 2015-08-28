<?php
/* @var $this yii\web\View */
/* @var $models common\models\Money[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Money;

$this->title =  '资金管理';
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['money/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'type','content'=>Money::TYPE_PAY])?>">充值记录</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'type','content'=>Money::TYPE_WITHDRAW])?>">提现记录</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'pay-more','content'=>'500'])?>">充值金额大于500用户</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'withdraw-more','content'=>'500'])?>">提现金额大于500用户</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'role','content'=>'A'])?>">A级</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'role','content'=>'AA'])?>">AA级</a>
            <a class="btn btn-default" href="<?=Url::to(['money/search','type'=>'role','content'=>'AAA'])?>">AAA级</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['money/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="nickname">用户名称</option>
                        <option value="pay-more">充值金额大于</option>
                        <option value="pay-equal">充值金额等于</option>
                        <option value="pay-less">充值金额小于</option>
                        <option value="withdraw-more">提现金额大于</option>
                        <option value="withdraw-equal">提现金额等于</option>
                        <option value="withdraw-less">提现金额小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户昵称</th>
                <th class="text-align-center">用户类型</th>
                <th class="text-align-center">金额</th>
                <th class="text-align-center">云豆数</th>
                <th class="text-align-center">时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->moneyId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td><?= $model->users['roleName'] ?></td>
                    <td>
                        <?php $icon = $model->type==Money::TYPE_PAY?'+':'-'?>
                        <?= $icon.$model->money ?>
                    </td>
                    <td>
                        <?= $icon.$model->bitcoin ?>
                    </td>
                    <td><?= $model->createDate ?></td>
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
