<?php
/* @var $this yii\web\View */
/* @var $models common\models\UsageMode[] */

$this->title = '消费方式';
?>
<div class="widget flat">
    <div class="widget-body">

        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">云豆收入或支出方式</th>
                <th class="text-align-center">收入或支出</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->usageModeId ?></td>
                    <td><?= $model->usageModeName ?></td>
                    <td><?= $model->typeName ?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>