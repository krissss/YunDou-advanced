<?php
/* @var $this yii\web\View */
/* @var $schemes common\models\Scheme[] */

use common\models\Scheme;
use common\models\Users;

$this->title = '提现设置';
$this->params['breadcrumbs'] = [
    '合作管理',
    $this->title
];

$userSession = Yii::$app->session->get('user');
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">方案名称</th>
                <th class="text-align-center">方案类型</th>
                <th class="text-align-center">累计云豆数</th>
                <th class="text-align-center">云豆：元</th>
                <th class="text-align-center">生效时间</th>
                <th class="text-align-center">结束时间</th>
                <th class="text-align-center">方案执行状态</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($schemes as $i=>$scheme):?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $scheme->name ?></td>
                    <td><?= $scheme->usageModelName ?></td>
                    <td><?= $scheme->totalBitcoin ?></td>
                    <td><?= $scheme->payBitcoin.":".intval($scheme->getMoney)?></td>
                    <td><?= $scheme->startDate==null?"永久":$scheme->startDate ?></td>
                    <td><?= $scheme->endDate==null?"永久":$scheme->endDate ?></td>
                    <td>
                        <?php if($scheme->level != Scheme::LEVEL_UNDO): //非基础方案?>
                        <?php if($userSession['role']>=Users::ROLE_MANAGER):?>
                        <label>
                            <?php if($scheme->state==Scheme::STATE_ABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen rebate-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>" checked>
                            <?php elseif($scheme->state==Scheme::STATE_DISABLE): ?>
                                <input class="checkbox-slider toggle colored-palegreen rebate-checkbox checked_<?=$scheme->schemeId?>" type="checkbox" data-id="<?=$scheme->schemeId?>">
                            <?php endif; ?>
                            <span class="text"></span>
                        </label>
                        <?php endif; ?>
                        <?php endif; ?>
                        <span class="state_<?=$scheme->schemeId?>"><?= $scheme->stateName ?></span>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>

