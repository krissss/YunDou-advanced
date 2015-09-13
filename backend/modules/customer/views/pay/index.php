<?php
/** @var $payForm \frontend\models\forms\RechargeForm */
/** @var $leftBitcoin */
/** @var $proportion */

use yii\widgets\ActiveForm;

$this->title = "云豆充值";

$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <?=\common\widgets\AlertWidget::widget()?>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-1 control-label margin-bottom-20'],
            ],
        ]); ?>
        <div class="form-group">
            <label class="col-md-1 control-label margin-bottom-20">您的账户余额</label>
            <div class="col-md-5">
                <p class="form-control-static"><?=$leftBitcoin;?>云豆</p>
            </div>
        </div>
        <?= $form->field($payForm, 'money')->textInput(['type'=>'number','name'=>'money','placeholder'=>'充值后不可返现']) ?>
        <div class="form-group">
            <label class="col-md-1 control-label margin-bottom-20">您能够获得</label>
            <div class="col-md-5">
                <input type="hidden" name="proportion" value="<?=$proportion;?>">
                <p class="form-control-static">
                    <span class="get_bitcoin">0</span>云豆
                </p>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-1 col-md-offset-1">
                <button type="button" class="btn btn-primary wxPay">微信支付</button>
                <!--<button type="button" class="btn btn-primary">支付宝支付</button>-->
            </div>
        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>