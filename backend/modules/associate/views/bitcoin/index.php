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

$user = Yii::$app->session->get('user');
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['bitcoin/index'])?>">所有</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['bitcoin/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <hr>
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="income-more">收入云豆大于</option>
                        <option value="income-equal">收入云豆等于</option>
                        <option value="income-less">收入云豆小于</option>
                        <option value="consume-more">支出云豆大于</option>
                        <option value="consume-equal">支出云豆等于</option>
                        <option value="consume-less">支出云豆小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-bordered table-striped margin-bottom-20">
            <tr>
                <td><strong>总计收入云豆：</strong><?=IncomeConsume::findTotalIncome($user->userId)?>(颗)</td>
                <td><strong>总计支出云豆: </strong><?=IncomeConsume::findTotalConsume($user->userId)?>(颗)</td>
            </tr>
        </table>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">收入或支出云豆</th>
                <th class="text-align-center">来源用户</th>
                <th class="text-align-center">方式</th>
                <th class="text-align-center">时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->incomeConsumeId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td>
                        <?php $icon = $model->type==IncomeConsume::TYPE_INCOME?'+':'-'?>
                        <?= $icon.$model->bitcoin ?>
                    </td>
                    <td><?= $model->fromUser['nickname'] ?></td>
                    <td><?= $model->usageMode['usageModeName'] ?></td>
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
