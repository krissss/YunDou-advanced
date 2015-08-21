<?php
/** @var $rechargeForm \frontend\models\forms\RechargeForm */

use yii\widgets\ActiveForm;

?>
<div class="alert alert-info" role="alert">支付接口申请中</div>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'id' => 'account-register',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-9 no-padding-left'>{input}</div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-3 control-label'],
        ],
    ]) ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">您的账户余额</label>
        <div class="col-xs-9 no-padding-left">
            <p class="form-control-static">1000</p>
        </div>
    </div>
    <?= $form->field($rechargeForm, 'money') ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">您能够获得的云豆数</label>
        <div class="col-xs-9 no-padding-left">
            <p class="form-control-static">10000</p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="submit" class="btn btn-primary">微信支付</button>
            <button type="submit" class="btn btn-primary">支付宝支付</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
