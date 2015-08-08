<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */
/* @var $searchModel backend\models\UsersSearch */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '充值设置';
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="<?=Url::to(['income-consume/index'])?>"  ><i class="fa fa-plus"></i>添加新方案</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询方案</a>
            <hr>
            <div class="view">
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['income-consume/index'])?>">所有</a>
<!--                <a class="btn btn-default" href="--><?//=Url::to(['income-consume/search','type'=>'usageModeName','content'=>'充值'])?><!--">充值用户</a>-->
<!--                <a class="btn btn-default" href="javascript:void(0);">收入云豆用户</a>-->
<!--                <a class="btn btn-default" href="javascript:void(0);">支出云豆用户</a>-->
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['income-consume/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">A方案</option>
                        <option value="role">B方案</option>
                        <option value="username">C方案</option>
<!--                        <option value="bitcoin">收入云豆数大于</option>-->
<!--                        <option value="bitcoin">收入云豆数等于</option>-->
<!--                        <option value="bitcoin">收入云豆数小于</option>-->
<!--                        <option value="bitcoin">支出云豆数大于</option>-->
<!--                        <option value="bitcoin">支出云豆数等于</option>-->
<!--                        <option value="bitcoin">支出云豆数小于</option>-->
                        <!--                        <option value="testChapterId">余额大于</option>-->
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">方案名称</th>
                <th class="text-align-center">消费起始点（元）</th>
                <th class="text-align-center">充值比例</th>
                <th class="text-align-center">生效时间</th>
                <th class="text-align-center">方案执行状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
<!--            --><?//=print_r($models)?>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->incomeConsumeId ?></td>
                    <td>A方案</td>
                    <td>1</td>
                    <td>100</td>
                    <td>2015年8月15日</td>
                    <td>执行</td>
                    <td>
                       <a href="<?= Url::to(['income-consume/update', 'incomeConsumeId' => $model->incomeConsumeId]) ?>"><span class="glyphicon glyphicon-pencil">  </span></a>
                       <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'incomeConsumeId' => $model->incomeConsumeId], [
                            'data' => [
                                'confirm' => '你确定要删除这条信息记录吗？',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </td>
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
