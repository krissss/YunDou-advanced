<?php
/* @var $this yii\web\View */
/* @var $models common\models\Money[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Money;

$this->title = '充值记录';
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
            <a class="btn btn-default" href="<?=Url::to(['recharge/index'])?>">所有</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['recharge/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <hr>
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="pay-more">充值金额大于</option>
                        <option value="pay-equal">充值金额等于</option>
                        <option value="pay-less">充值金额小于</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-bordered table-striped margin-bottom-20">
            <tr>
                <td><strong>总计充值金额：</strong><?=Money::findTotalPay($user['userId'])?>(元）<td>
            </tr>
        </table>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户昵称</th>
                <th class="text-align-center">用户真实姓名</th>
                <th class="text-align-center">充值金额</th>
                <th class="text-align-center">获得云豆数</th>
                <th class="text-align-center">时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->moneyId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td><?= $model->users['realname'] ?></td>
                    <td><?= $model->money ?></td>
                    <td><?= $model->bitcoin ?></td>
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
