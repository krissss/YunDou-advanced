<?php
/** @var $incomeConsumes \common\models\IncomeConsume[] */

use common\models\IncomeConsume;
use common\functions\CommonFunctions;

$user = Yii::$app->session->get('user');
$userIcon = isset($user['userIcon'])?$user['userIcon']:null;
$userIcon = CommonFunctions::createHttpImagePath($userIcon);
?>
<div class="account-header">
    <div class="avatar">
        <img src="<?=$userIcon?>" alt="head">
        <p><?=$user['nickname']?></p>
        <p>云豆余额：<strong><?=$user['bitcoin']?></strong></p>
    </div>
</div>
<hr>
<div class="container-fluid">
    <h4>云豆收入支出记录</h4>
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
                    $class = "glyphicon glyphicon-plus";
                }elseif($incomeConsume['type']==IncomeConsume::TYPE_CONSUME){
                    $class = "glyphicon glyphicon-minus";
                }
                ?>
                <td><span class="small <?=$class?>"></span><?=$incomeConsume['bitcoin']?></td>
                <td><?=$incomeConsume->usageMode['usageModeName']?></td>
                <td><?=$incomeConsume['createDate']?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>
