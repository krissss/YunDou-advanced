<?php
/* @var $this yii\web\View */
/* @var $pages */
/* @var $models \common\models\Info[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '报名';
?>
<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <div class="view">
                <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['sign-up/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['sign-up/search','type'=>'state','content'=>'record'])?>">待填报</a>
                <a class="btn btn-default" href="<?=Url::to(['sign-up/search','type'=>'state','content'=>'pass'])?>">已填报</a>
                <a class="btn btn-default" href="<?=Url::to(['sign-up/search','type'=>'state','content'=>'refuse'])?>">填报失败</a>
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['sign-up/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="IDCard">身份证</option>
                        <option value="cellphone">手机号</option>
                        <option value="realName">姓名</option>
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
                <th class="text-align-center">姓名</th>
                <th class="text-align-center">身份证</th>
                <th class="text-align-center">手机号</th>
                <th class="text-align-center">填报时间</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->infoId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->realName ?></td>
                    <td><?= $model->IDCard ?></td>
                    <td><?= $model->cellphone ?></td>
                    <td><?= $model->createDate ?></td>
                    <td class="state_<?=$model->infoId?>"><?= $model->stateName ?></td>
                    <td>
                        <?php if($model->state==\common\models\Info::STATE_RECORD): ?>
                        <button class="btn btn-xs btn-default view_info btn_<?=$model->infoId?>" data-id="<?=$model->infoId?>">
                            <span class="fa fa-edit"></span>查看
                        </button>
                        <?php endif;?>
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