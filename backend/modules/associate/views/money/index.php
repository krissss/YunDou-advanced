<?php
/* @var $this yii\web\View */
/* @var $models common\models\IncomeConsume[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\IncomeConsume;

$this->title = '提现记录';
?>

<div class="widget flat">
    <div class="widget-body">
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">提现金额(元)</th>
                <th class="text-align-center">消耗云豆(颗)</th>
                <th class="text-align-center">提现时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->users['nickname'] ?></td>
                    <td>+<?= $model->bitcoin/100?></td>
                    <td>-<?= $model->bitcoin?></td>
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
