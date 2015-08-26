<?php
/** @var $incomeConsumes \common\models\IncomeConsume[] */

use yii\helpers\Url;
use common\models\IncomeConsume;
use common\functions\CommonFunctions;
use common\models\Users;

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
    <h4 class="col-xs-8">云豆收入支出记录</h4>
    <a href="<?= Url::to(['account/pay-record']) ?>" class="btn btn-primary pull-right">充值记录</a>
    <table class="table table-striped text-center">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">云豆</th>
                <th class="text-center">方式</th>
                <th class="text-center">日期</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($incomeConsumes as $i=>$incomeConsume): ?>
            <tr>
                <th scope="row"><?=$i+1?></th>
                <?php
                if($incomeConsume['type']==IncomeConsume::TYPE_INCOME){
                    $icon = '+';
                }elseif($incomeConsume['type']==IncomeConsume::TYPE_CONSUME){
                    $icon = '-';
                }
                ?>
                <td><?=$icon.$incomeConsume['bitcoin']?></td>
                <td><?=$incomeConsume->usageMode['usageModeName']?></td>
                <td><?=$incomeConsume['createDate']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
