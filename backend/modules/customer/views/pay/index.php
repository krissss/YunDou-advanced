<?php
/** @var $payForm \frontend\models\forms\RechargeForm */
/** @var $leftBitcoin */
/** @var $proportion */

use yii\widgets\ActiveForm;

$this->title = "云豆充值";
$rebateScheme = Yii::$app->session->getFlash('rebateScheme');
?>
<div class="load-container loading my_hide">
    <div class="loader">Loading...</div>
    <p>订单生成中，请耐心等待。。。</p>
</div>
<?=\common\widgets\AlertWidget::widget()?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'id' => 'account-register',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-7 no-padding-left'>{input}</div><div class='col-xs-7 col-xs-offset-5'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-5 control-label'],
        ],
    ]) ?>
    <div class="form-group">
        <label class="col-xs-5 control-label">您的账户余额</label>
        <div class="col-xs-7 no-padding-left">
            <p class="form-control-static"><?=$leftBitcoin;?>云豆</p>
        </div>
    </div>
    <?= $form->field($payForm, 'money')->textInput(['type'=>'number','name'=>'money','placeholder'=>'充值后不可返现']) ?>
    <div class="form-group">
        <label class="col-xs-5 control-label">您能够获得</label>
        <div class="col-xs-7 no-padding-left">
            <input type="hidden" name="proportion" value="<?=$proportion;?>">
            <p class="form-control-static">
                <span class="get_bitcoin">0</span>
                <?php if($rebateScheme):?>
                    + <span class="rebate_bitcoin" data-money="<?=$rebateScheme['payMoney']?>" data-rebate="<?=$rebateScheme['rebateSelf']?>">0</span>
                    = <span class="total_bitcoin">0</span>
                <?php endif;?>
                云豆
            </p>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-2 col-xs-5 no-padding-left">
            <button type="submit" class="btn btn-primary">微信支付</button>
            <!--<button type="button" class="btn btn-primary">支付宝支付</button>-->
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>