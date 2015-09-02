<?php
/** @var $incomeConsumes \common\models\IncomeConsume[] */
/** @var $pages */

use yii\helpers\Url;
use common\models\IncomeConsume;
use common\functions\CommonFunctions;
use common\models\Users;
use common\functions\DateFunctions;

$this->title = "我的账户";

$user = Yii::$app->session->get('user');
$userIcon = isset($user['userIcon'])?$user['userIcon']:null;
$userIcon = CommonFunctions::createHttpImagePath($userIcon);
?>
<div class="account-header">
    <div class="avatar">
        <img src="<?=$userIcon?>" alt="head">
        <p><?=$user['nickname']?></p>
        <p>云豆余额：<strong><?=Users::findBitcoin($user['userId'])?></strong></p>
    </div>
</div>
<hr>
<div class="container-fluid">
    <p>
        <strong>云豆收入支出</strong>
        <a href="<?= Url::to(['account/pay-record']) ?>" class="btn btn-primary btn-sm pull-right">充值记录</a>
    </p>
    <table class="table table-striped">
        <tbody>
            <tr>
                <td>总计获得:+<?=IncomeConsume::findTotalIncome($user['userId'])?>颗云豆</td>
                <td>总计支出:-<?=IncomeConsume::findTotalConsume($user['userId'])?>颗云豆</td>
            </tr>
        </tbody>
    </table>
    <p><strong>详细记录</strong></p>
    <table class="table table-striped text-center no-margin-bottom">
        <thead>
            <tr>
                <th class="text-center">云豆</th>
                <th class="text-center">方式</th>
                <th class="text-center">日期</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($incomeConsumes as $i=>$incomeConsume): ?>
            <tr>
                <?php
                if($incomeConsume['type']==IncomeConsume::TYPE_INCOME){
                    $icon = '+';
                }elseif($incomeConsume['type']==IncomeConsume::TYPE_CONSUME){
                    $icon = '-';
                }else{
                    $icon = '';
                }
                ?>
                <td><?=$icon.$incomeConsume['bitcoin']?></td>
                <td><?=$incomeConsume->usageMode['usageModeName']?></td>
                <td><?=DateFunctions::getDate($incomeConsume['createDate'])?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <nav class="pull-right pagination_footer">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </nav>
    <div class="clearfix"></div>

</div>
