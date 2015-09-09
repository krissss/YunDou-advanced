<?php
/* @var $this yii\web\View */
/* @var $user common\models\Users */

use common\models\Users;
use common\models\Money;
use common\models\IncomeConsume;
use yii\helpers\Url;

$this->title = '我的信息';
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <h4>我的信息</h4>
        <div class="form-title"></div>
        <table class="table table_four_column">
            <tbody>
                <tr>
                    <td class="text-align-right table_bg_grey">登录名称</td>
                    <td><?= $user->username ?></td>
                    <td class="text-align-right table_bg_grey">昵称</td>
                    <td><?= $user->nickname ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">推荐码</td>
                    <td><?= $user->recommendCode ?></td>
                    <td class="text-align-right table_bg_grey">累计充值金额</td>
                    <td><?= Money::findTotalPay($user->userId) ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">累计云豆数</td>
                    <td><?= IncomeConsume::findTotalIncome($user->userId) ?></td>
                    <td class="text-align-right table_bg_grey">剩余云豆</td>
                    <td><?= Users::findBitcoin($user['userId']) ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">关联用户数</td>
                    <td><?= count(Users::findBeRecommend($user->userId)) ?></td>
                    <td class="text-align-right table_bg_grey">地址</td>
                    <td><?= $user->address ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">联系人</td>
                    <td><?= $user->realname ?></td>
                    <td class="text-align-right table_bg_grey">电话</td>
                    <td><?= $user->cellphone ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">邮件</td>
                    <td><?= $user->email ?></td>
                    <td class="text-align-right table_bg_grey">QQ</td>
                    <td><?= $user->qq ?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">微信</td>
                    <td><?= $user->weixin ?></td>
                    <td class="text-align-right table_bg_grey">状态</td>
                    <td><?= $user->stateName ?></td>
                </tr>
            </tbody>
        </table>
        <a href="<?=Url::to(['default/modify-password']) ?>" class="btn btn-primary">修改密码</a>
    </div>
</div>

