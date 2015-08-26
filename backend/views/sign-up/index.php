<?php
/* @var $this yii\web\View */
/* @var $pages */
/* @var $models \common\models\Info[] */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Info;

$this->title = '报名';
?>
<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <div class="view">
                <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['info/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['info/search','type'=>'date','content'=>'-1 day'])?>">一天内</a>
                <a class="btn btn-default" href="<?=Url::to(['info/search','type'=>'state','content'=>'noReply'])?>">未回复</a>
                <a class="btn btn-default" href="<?=Url::to(['info/search','type'=>'state','content'=>'replied'])?>">已回复</a>
                <a class="btn btn-default" href="<?=Url::to(['info/search','type'=>'state','content'=>'publish'])?>">已发布</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['info/search'], 'post', ['class' => 'form-inline']) ?>
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
                <th class="text-align-center">状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->infoId ?></td>
                    <td><?= $model->createUser['userId'] ?></td>
                    <td class="nickname_<?=$model->infoId?>"><?= $model->createUser['nickname'] ?></td>
                    <td class="text-align-left content_<?=$model->infoId?>"><?= $model->content ?></td>
                    <td><?= $model->createDate ?></td>
                    <td class="text-align-left"><?= $model->reply ?></td>
                    <td><?= $model->replyUser['nickname'] ?></td>
                    <td><?= $model->replyDate ?></td>
                    <td><span class="state_<?=$model->infoId?>"><?= $model->stateName ?></span></td>
                    <td class="text-align-left">
                        <button class="btn btn-xs btn-default reply_info" data-toggle="modal" data-target="#reply_info" data-id="<?=$model->infoId?>">
                            <span class="fa fa-edit"></span>查看
                        </button>
                        <?php if($model->state==Info::STATE_RECORD):?>
                            <button class="btn btn-xs btn-default pass" data-id="<?=$model->serviceId?>">等待报名</button>
                        <?php elseif($model->state==Info::STATE_PASS):?>
                            <button class="btn btn-xs btn-default pass" data-id="<?=$model->serviceId?>">报名完成</button>
                        <?php else: ?>
                        <?php endif; ?>
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
                <input type="hidden" name="serviceId" value="">
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
                <button type="submit" name="publish" value="publish" class="btn btn-primary">保存并发布</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>