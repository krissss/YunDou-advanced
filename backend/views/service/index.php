<?php
/* @var $this yii\web\View */
/* @var $pages */
/* @var $models \common\models\Service[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '咨询管理';
$this->registerJsFile('js/service.js',['depends'=>['backend\assets\AppAsset']]);
?>
<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <hr>
            <div class="view">
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['service/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['service/search','type'=>'date','content'=>'-1 day'])?>">一天内</a>
                <a class="btn btn-default" href="<?=Url::to(['service/search','type'=>'state','content'=>'noReply'])?>">未回复</a>
                <a class="btn btn-default" href="<?=Url::to(['service/search','type'=>'state','content'=>'replied'])?>">已回复</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['service/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="nickname">用户名称</option>
                        <option value="content">咨询问题</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户昵称</th>
                <th class="text-align-center">咨询问题</th>
                <th class="text-align-center">咨询时间</th>
                <th class="text-align-center">回复</th>
                <th class="text-align-center">回复人</th>
                <th class="text-align-center">回复时间</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->serviceId ?></td>
                    <td><?= $model->createUser['userId'] ?></td>
                    <td class="nickname_<?=$model->serviceId?>"><?= $model->createUser['nickname'] ?></td>
                    <td class="content_<?=$model->serviceId?>"><?= $model->content ?></td>
                    <td><?= $model->createDate ?></td>
                    <td><?= $model->reply ?></td>
                    <td><?= $model->replyUser['nickname'] ?></td>
                    <td><?= $model->replyDate ?></td>
                    <td>
                        <a class="reply_service" href="#" data-toggle="modal" data-target="#reply_service" title="回复" data-id="<?=$model->serviceId?>"><span class="fa fa-edit"></span></a>
                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'serviceId' => $model->serviceId], [
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

<div class="modal fade" id="reply_service" tabindex="-1" role="dialog" aria-labelledby="回复咨询">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">修改模板</h4>
            </div>
            <?=Html::beginForm(['service/reply'], 'post', ['class' => 'form-horizontal']);?>
            <div class="modal-body">
                <input class="serviceId" type="hidden" name="serviceId" value="">
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">咨询者</label>
                    <p class="col-sm-9 reply_nickname"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">咨询问题</label>
                    <p class="col-sm-9 reply_content"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">回复</label>
                    <textarea class="col-sm-9" name="reply" cols="10" rows="4"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>