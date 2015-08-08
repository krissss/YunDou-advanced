<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */
/* @var $searchModel backend\models\UsersSearch */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', '题库管理');
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="<?=Url::to(['testlibrary/create'])?>"><i class="fa fa-plus"></i>添加题目</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询题目</a>
            <a class="btn btn-default" href="<?=Url::to(['users/upusers'])?>"><i class="fa fa-upload"></i>导入题目</a>
            <a class="btn btn-default" href="javascript:void(0);"><i class="fa fa-download"></i>导出题目</a>
            <hr>
            <div class="view">
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['test-library/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'testType','content'=>'单选'])?>">单选</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'testType','content'=>'多选'])?>">多选</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'testType','content'=>'判断'])?>">判断</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'testType','content'=>'案例与计算'])?>">案例与计算</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'province','content'=>'江苏'])?>">江苏试题</a>
                <a class="btn btn-default" href="<?=Url::to(['test-library/search','type'=>'province','content'=>'安徽'])?>">安徽试题</a>
<!--                <a class="btn btn-default" href="javascript:void(0);">7天未登录</a>-->
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['test-library/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="preTypeId">课程类别</option>
                        <option value="province">所属省别</option>
                        <option value="majorJob">专业岗位</option>
                        <option value="testChapterId">章节</option>
                        <option value="question">问题</option>

                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>  <th class="text-align-center">题目编号</th>
                <th class="text-align-center">题型</th>

                <th class="text-align-center">所属省别</th>
                <th class="text-align-center">专业岗位</th>
                <th class="text-align-center">课程类别</th>
                <th class="text-align-center">章节</th>
                <th class="text-align-center">每题分值</th>
                <th class="text-align-center">问题</th>
<!--                <th class="text-align-center">云豆余额</th>-->
<!--                <th class="text-align-center">用户等级</th>-->
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                     <td><?= $model->testLibraryId ?></td>
                    <td><?= $model->testType['name'] ?></td>
                    <td><?=$model->province['name'] ?></td>
                    <td><?= $model->majorJob['name'] ?></td>
                    <td><?= $model->preType['name'] ?></td>
                    <td><?= $model->testChapter['name'] ?></td>
                    <td><?= $model->score ?></td>
                    <td><?= $model->question ?></td>
<!--                    <td>--><?//= $test->bitcoin ?><!--</td>-->
<!--                    <td>--><?//= $test->roleName ?><!--</td>-->
                    <td>
                        <a href="<?= Url::to(['test-library/update', 'testLibraryId' => $model->testLibraryId]) ?>"><span class="glyphicon glyphicon-pencil">  </span></a>
                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'testLibraryId' => $model->testLibraryId], [
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
