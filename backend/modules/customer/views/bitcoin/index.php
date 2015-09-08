<?php
/* @var $this yii\web\View */
/* @var $models common\models\IncomeConsume[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\IncomeConsume;

$this->title = '云豆收支';
$this->params['breadcrumbs'] = [
    $this->title
];
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['bitcoin/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['bitcoin/search','type'=>'income','content'=>'my'])?>">我的收入</a>
            <a class="btn btn-default" href="<?=Url::to(['bitcoin/search','type'=>'consume','content'=>'my'])?>">我的支出</a>
            <a class="btn btn-default" href="<?=Url::to(['bitcoin/search','type'=>'income','content'=>'others'])?>">获得云豆的用户</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['bitcoin/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <hr>
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="nickname">用户昵称</option>
                        <option value="realname">用户真实姓名</option>
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
                <th class="text-align-center">用户妮称</th>
                <th class="text-align-center">用户真实姓名</th>
                <th class="text-align-center">收入或支出云豆</th>
                <th class="text-align-center">来源或用途</th>
                <th class="text-align-center">时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->incomeConsumeId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td>
                        <?php $icon = $model->type==IncomeConsume::TYPE_INCOME?'+':'-'?>
                        <?= $icon.$model->bitcoin ?>
                    </td>
                    <td><?= $model->fromUser['nickname'] ?></td>
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
