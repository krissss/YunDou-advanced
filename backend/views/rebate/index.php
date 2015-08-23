<?php
/* @var $this yii\web\View */
/* @var $schemes common\models\Scheme[] */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Scheme;

$this->title = '充值返点设置';
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default add_rebate" href="javascript:void(0);"><i class="fa fa-plus"></i>添加新方案</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询方案</a>
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['rebate/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['rebate/search','type'=>'state','content'=>Scheme::STATE_ABLE])?>">已启用</a>
                <a class="btn btn-default" href="<?=Url::to(['rebate/search','type'=>'state','content'=>Scheme::STATE_DISABLE])?>">未启用</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['rebate/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="name">方案名称</option>
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
                <th class="text-align-center">起始消费</th>
                <th class="text-align-center">消费返点</th>
                <th class="text-align-center">生效时间</th>
                <th class="text-align-center">结束时间</th>
                <th class="text-align-center">方案执行状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($schemes as $i=>$scheme):?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $scheme->name ?></td>
                    <td><?= $scheme->payMoney ?></td>
                    <td><?= ($scheme->rebate)*100 ?>%</td>
                    <td><?= $scheme->startDate==null?"永久":$scheme->startDate ?></td>
                    <td><?= $scheme->endDate==null?"永久":$scheme->endDate ?></td>
                    <td>
                        <?php if($scheme->level != Scheme::LEVEL_UNDO): //非基础方案?>
                        <label>
                            <?php if($scheme->state==Scheme::STATE_ABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen rebate-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>" checked>
                            <?php elseif($scheme->state==Scheme::STATE_DISABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen rebate-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>">
                            <?php endif; ?>
                            <span class="text"></span>
                        </label>
                        <?php endif; ?>
                        <span class="state_<?=$scheme->schemeId?>"><?= $scheme->stateName ?></span>
                    </td>
                    <td>
                        <?php if($scheme->level != Scheme::LEVEL_UNDO): //非基础方案?>
                        <button class="btn btn-xs btn-default update_rebate" data-id="<?=$scheme->schemeId?>">
                            <span class="fa fa-edit"></span>编辑
                        </button>
                        <a class="btn btn-xs btn-default rebate-delete" data-id="<?=$scheme->schemeId?>"
                           href="<?=Url::to(['rebate/delete','id'=>$scheme->schemeId])?>">
                            <i class="fa fa-trash-o"></i>删除
                        </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

