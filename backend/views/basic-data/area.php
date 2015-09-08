<?php
/* @var $this yii\web\View */
/* @var $models common\models\Province[] */

use yii\helpers\Html;

$this->title = '区域类型';
$this->params['breadcrumbs'] = [
    '基础数据',
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="modal" data-target="#area_modal"><i class="fa fa-plus"></i>添加区域</a>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">名称</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->provinceId ?></td>
                    <td><?= $model->name ?></td>
                    <td>
                        <button class="btn btn-xs btn-default update_area_major"
                                data-toggle="modal" data-target="#area_modal" data-id="<?=$model->provinceId?>" data-name="<?= $model->name ?>">
                            <span class="fa fa-edit"></span>修改
                        </button>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="area_modal" tabindex="-1" role="dialog" aria-labelledby="添加或更新区域" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">添加新区域</h4>
            </div>
            <?=Html::beginForm(['basic-data/area'], 'post', ['class' => 'form-inline']);?>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon">区域名称</span>
                        <input type="hidden" name="id" value="">
                        <input class="form-control" type="text" name="name">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="submit" class="btn btn-primary">提交</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>
