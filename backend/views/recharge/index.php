<?php
/* @var $this yii\web\View */
/* @var $schemes common\models\Scheme[] */

use yii\helpers\Url;
use common\models\Scheme;
use common\models\Users;

$this->title = '充值管理';
$this->params['breadcrumbs'] = [
    '云豆管理',
    $this->title
];

$userSession = Yii::$app->session->get('user');
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <?php if($userSession['role']>=Users::ROLE_MANAGER):?>
            <a class="btn btn-default add_recharge" href="javascript:void(0);"><i class="fa fa-plus"></i>添加新方案</a>
            <?php endif; ?>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['recharge/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['recharge/search','type'=>'state','content'=>Scheme::STATE_ABLE])?>">已启用</a>
            <a class="btn btn-default" href="<?=Url::to(['recharge/search','type'=>'state','content'=>Scheme::STATE_DISABLE])?>">未启用</a>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">方案名称</th>
                <th class="text-align-center">充值金额</th>
                <th class="text-align-center">获得云豆</th>
                <th class="text-align-center">生效时间</th>
                <th class="text-align-center">结束时间</th>
                <th class="text-align-center">方案执行状态</th>
                <?php if($userSession['role']>=Users::ROLE_MANAGER):?>
                <th class="text-align-center">操作</th>
                <?php endif; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach($schemes as $i=>$scheme):?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $scheme->name ?></td>
                    <td><?= $scheme->payMoney ?></td>
                    <td><?= $scheme->getBitcoin ?></td>
                    <td><?= $scheme->startDate==null?"永久":$scheme->startDate ?></td>
                    <td><?= $scheme->endDate==null?"永久":$scheme->endDate ?></td>
                    <td>
                        <?php if($scheme->level != Scheme::LEVEL_UNDO): //非基础方案?>
                        <?php if($userSession['role']>=Users::ROLE_MANAGER):?>
                        <label>
                            <?php if($scheme->state==Scheme::STATE_ABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen recharge-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>" checked>
                            <?php elseif($scheme->state==Scheme::STATE_DISABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen recharge-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>">
                            <?php endif; ?>
                            <span class="text"></span>
                        </label>
                        <?php endif; ?>
                        <?php endif; ?>
                        <span class="state_<?=$scheme->schemeId?>"><?= $scheme->stateName ?></span>
                    </td>
                    <?php if($userSession['role']>=Users::ROLE_MANAGER):?>
                    <td>
                        <?php if($scheme->level != Scheme::LEVEL_UNDO): //非基础方案?>
                        <button class="btn btn-xs btn-default update_recharge" data-id="<?=$scheme->schemeId?>">
                            <span class="fa fa-edit"></span>编辑
                        </button>
                        <a class="btn btn-xs btn-default recharge-delete" data-id="<?=$scheme->schemeId?>"
                           href="<?=Url::to(['recharge/delete','id'=>$scheme->schemeId])?>">
                            <i class="fa fa-trash-o"></i>删除
                        </a>
                        <?php endif; ?>
                    </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

