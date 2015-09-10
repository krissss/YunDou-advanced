<?php
/* @var $this yii\web\View */
/* @var $user common\models\User */

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
        <p>
            <strong>基本信息</strong>
            <a href="<?=Url::to(['default/modify-password']) ?>" class="btn btn-xs btn-primary margin-left-20">修改密码</a>
        </p>
        <table class="table table_four_column">
            <tbody>
            <tr>
                <td>登录名称</td>
                <td><?= $user->username ?></td>
                <td>累计提现金额</td>
                <td><?= Money::findTotalWithdraw($user->userId) ?>（元）</td>
            </tr>
            <tr>
                <td>推荐码</td>
                <td><?= $user->recommendCode ?></td>
                <td>累计获得云豆数</td>
                <td><?= IncomeConsume::findTotalIncome($user->userId) ?>（颗）</td>

            </tr>
            <tr>
                <td>关联用户数</td>
                <td><?= count(Users::findBeRecommend($user->userId)) ?></td>
                <td>剩余云豆</td>
                <td><?= $user->bitcoin ?>（颗）</td>
            </tr>
            <tr>
                <td>状态</td>
                <td><?= $user->stateName ?></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
        <p class="margin-top-10">
            <strong>其他信息</strong>
            <a href="<?=Url::to(['default/update-user']) ?>" class="btn btn-xs btn-primary margin-left-20">信息更改</a>
        </p>
        <table class="table table_four_column">
            <tr>
                <td>昵称</td>
                <td><?= $user->nickname ?></td>
                <td>联系人</td>
                <td><?= $user->realname ?></td>

            </tr>
            <tr>
                <td>电话</td>
                <td><?= $user->cellphone ?></td>
                <td>QQ</td>
                <td><?= $user->qq ?></td>
            </tr>
            <tr>
                <td>地址</td>
                <td><?= $user->address ?></td>
                <td>微信</td>
                <td><?= $user->weixin ?></td>
            </tr>
            <tr>
                <td>邮件</td>
                <td><?= $user->email ?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <p class="margin-top-10">
            <strong>银行卡信息</strong>
            <a href="<?=Url::to(['default/update-bank']) ?>" class="btn btn-xs btn-primary margin-left-20">管理银行卡</a>
        </p>
        <table class="table table_four_column">
            <tr>
                <td>开户行</td>
                <td><?= $user->bankCard['bankName'] ?></td>
                <td>银行账号</td>
                <td><?= $user->bankCard['cardNumber'] ?></td>
            </tr>
            <tr>
                <td>账户名称</td>
                <td><?= $user->bankCard['cardName'] ?></td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

