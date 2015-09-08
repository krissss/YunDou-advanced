<?php
/* @var $this yii\web\View */
/* @var $models common\models\TestChapter[] */
/* @var $pages */

$this->title = '试题章节';
$this->params['breadcrumbs'] = [
    '基础数据',
    $this->title
];
?>

<div class="widget flat">
    <div class="widget-body">
        <!--<div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-plus"></i>添加分类</a>
        </div>-->
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">名称</th>
                <th class="text-align-center">所属类型</th>
                <th class="text-align-center">所属专业岗位</th>
                <!--<th class="text-align-center">操作</th>-->
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->testChapterId ?></td>
                    <td><?= $model->name ?></td>
                    <td><?= $model->preType['name'] ?></td>
                    <td><?= $model->majorJob['name'] ?></td>
                    <!--<td>
                        <button class="btn btn-xs btn-default update_area_major"
                                data-toggle="modal" data-target="#major_modal" data-id="<?/*=$model->majorJobId*/?>" data-name="<?/*= $model->name */?>">
                            <span class="fa fa-edit"></span>修改
                        </button>
                    </td>-->
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
