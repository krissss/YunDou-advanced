<?php
/* @var $this yii\web\View */
/* @var $models common\models\IncomeConsume[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\IncomeConsume;

$this->title = Yii::t('app', '云豆管理');
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'usageModeName','content'=>'充值'])?>">充值用户</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'type','content'=>'0'])?>">收入云豆用户</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'type','content'=>'1'])?>">支出云豆用户</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'income-more','content'=>'999'])?>">充值大于1000用户</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'income-more','content'=>'4999'])?>">收入大于5000用户</a>
            <a class="btn btn-default" href="<?=Url::to(['income-consume/search','type'=>'pay-more','content'=>'2999'])?>">支出大于3000用户</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['income-consume/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <hr>
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="nickname">用户名称</option>
                        <option value="income-more">收入云豆数大于</option>
                        <option value="income-equal">收入云豆数等于</option>
                        <option value="income-less">收入云豆数小于</option>
                        <option value="pay-more">支出云豆数大于</option>
                        <option value="pay-equal">支出云豆数等于</option>
                        <option value="pay-less">支出云豆数小于</option>
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
                <th class="text-align-center">用户类型</th>
                <th class="text-align-center">收入或支出云豆</th>
                <th class="text-align-center">收入或支出方式</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->incomeConsumeId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td><?= $model->users['roleName'] ?></td>
                    <td><?= $model->bitcoin ?></td>
                    <td><?= $model->usageMode['usageModeName'] ?></td>
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
