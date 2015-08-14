<?php
/* @var $this yii\web\View */
/* @var $models common\models\TestType[] */

$this->title = '试题类型';
?>
<div class="widget flat">
    <div class="widget-body">

    <table class="table table-hover table-bordered text-align-center">
        <thead class="bordered-blue">
        <tr>
            <th class="text-align-center">序号</th>
            <th class="text-align-center">测试类型</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($models as $model):?>
            <tr>
                <td><?= $model->testTypeId ?></td>
                <td><?= $model->name ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
    </div>
</div>