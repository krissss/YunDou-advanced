<?php
/* @var $this yii\web\View */
/* @var $examTemplates common\models\ExamTemplate[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\ExamTemplate;

$this->title = '模版管理';
$this->params['breadcrumbs'] = [
    $this->title
];
$session = Yii::$app->session;
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create_template">
                <i class="fa fa-plus"></i>添加新模板
            </button>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['exam-template/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['exam-template/search','type'=>'province','content'=>'江苏'])?>">江苏</a>
            <a class="btn btn-default" href="<?=Url::to(['exam-template/search','type'=>'province','content'=>'安徽'])?>">安徽</a>
            <a class="btn btn-default" href="<?=Url::to(['exam-template/search','type'=>'state','content'=>'F'])?>">未启用</a>
            <a class="btn btn-default" href="<?=Url::to(['exam-template/search','type'=>'state','content'=>'T'])?>">已启用</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['exam-template/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="name">模板名称</option>
                        <option value="province">省份</option>
                        <option value="majorJob">专业岗位</option>
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
                <th class="text-align-center">模板编号</th>
                <th class="text-align-center">模板名称</th>
                <th class="text-align-center">专业岗位</th>
                <th class="text-align-center">省份</th>
                <th class="text-align-center">创建日期</th>
                <th class="text-align-center">创建者</th>
                <th class="text-align-center">总题数</th>
                <th class="text-align-center">总分数</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($examTemplates as $examTemplate):?>
                <tr>
                    <td><?= $examTemplate->examTemplateId ?></td>
                    <td><a href="<?=Url::to(['exam-template/view','id'=>$examTemplate->examTemplateId])?>"><?= $examTemplate->name ?></a></td>
                    <td><?= $examTemplate->majorJob['name'] ?></td>
                    <td><?= $examTemplate->province['name'] ?></td>
                    <td><?= $examTemplate->createDate ?></td>
                    <td><?= $examTemplate->createUser['nickname'] ?></td>
                    <td class="question_count_<?=$examTemplate->examTemplateId?>"><?= $examTemplate->questionCount ?></td>
                    <td><?= $examTemplate->questionScore ?></td>
                    <td>
                        <label>
                        <?php if($examTemplate->state==ExamTemplate::STATE_ABLE): ?>
                            <input class="checkbox-slider toggle colored-palegreen template-checkbox checked_<?=$examTemplate->examTemplateId?>" type="checkbox" data-id="<?=$examTemplate->examTemplateId?>" checked>
                        <?php elseif($examTemplate->state==ExamTemplate::STATE_DISABLE): ?>
                            <input class="checkbox-slider toggle colored-palegreen template-checkbox checked_<?=$examTemplate->examTemplateId?>" type="checkbox" data-id="<?=$examTemplate->examTemplateId?>">
                        <?php endif; ?>
                            <span class="text"></span>
                        </label>
                        <span class="state_<?=$examTemplate->examTemplateId?>"><?= $examTemplate->stateName ?></span>
                    </td>
                    <td>
                        <a class="template-edit" data-id="<?=$examTemplate->examTemplateId?>" href="<?=Url::to(['exam-template/create-detail','id'=>$examTemplate->examTemplateId])?>" title="编辑题目"><i class="fa fa-pencil"></i></a>
                        <a class="template-delete" data-id="<?=$examTemplate->examTemplateId?>" href="<?=Url::to(['exam-template/delete','id'=>$examTemplate->examTemplateId])?>" title="删除模板"><i class="fa fa-trash-o"></i></a>
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

<div class="modal fade" id="create_template" tabindex="-1" role="dialog" aria-labelledby="创建新模板">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">创建新模板</h4>
            </div>
            <?=Html::beginForm(['exam-template/create-template'], 'post', ['class' => 'form-inline']);?>
            <div class="modal-body">
                <div class="form-group">
                    <select class="form-control" name="provinceId">
                        <option value="">选择省份</option>
                        <?php $provinces = $session->get('provinces');?>
                        <?php foreach($provinces as $province):?>
                        <option value="<?=$province['provinceId']?>"><?=$province['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <select class="form-control" name="majorJobId">
                        <option value="">选择专业岗位</option>
                        <?php $majorJobs = $session->get('majorJobs');?>
                        <?php foreach($majorJobs as $majorJob):?>
                            <option value="<?=$majorJob['majorJobId']?>"><?=$majorJob['name']?></option>
                        <?php endforeach;?>
                    </select>
                    <div class="input-group">
                        <span class="input-group-addon">模板名称</span>
                        <input class="form-control" type="text" name="name" placeholder="请输入模板名称">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
                <button type="submit" name="continue" value="continue" class="btn btn-primary">保存并添加题目</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>

<div class="modal fade" id="update_template" tabindex="-1" role="dialog" aria-labelledby="修改模板">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改模板</h4>
            </div>
            <?=Html::beginForm(['exam-template/index'], 'post', ['class' => 'form-inline']);?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">省份</label>
                    <span class="province">江苏省</span>
                    <label for="">专业岗位</label>
                    <span class="province">试验员</span>
                    <div class="input-group">
                        <span class="input-group-addon">模板名称</span>
                        <input class="form-control" type="text" name="name" placeholder="请输入模板名称">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
                <button type="submit" name="continue" value="continue" class="btn btn-primary">保存并添加题目</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>
