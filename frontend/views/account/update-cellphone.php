<?php
/** @var $updateCellphoneForm \frontend\models\forms\UpdateInfoForm */

use yii\widgets\ActiveForm;

$this->title = "更新手机号";

?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'id' => 'account-update-cellphone',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-8 no-padding-left'>{input}</div><div class='col-xs-8 col-xs-offset-4'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-4 control-label'],
        ],
    ]) ?>
    <?= $form->field($updateCellphoneForm, 'cellphone')->textInput(['class'=>'form-control mobile','type'=>'number']) ?>
    <?= $form->field($updateCellphoneForm, 'verifyCode',[
        'template' => "{label}{input}<div class='col-xs-8 col-xs-offset-4'>{error}</div>",
    ])->widget(\yii\captcha\Captcha::className(), [
        'name'=>'verifyCode',
        'template' => '<div class="col-xs-4 no-padding-left">{input}</div><div class="col-xs-4 no-padding-left" style="height: 34px;">{image}</div>',
    ]);?>
    <?= $form->field($updateCellphoneForm, 'yzm', [
        'template' => "{label}<div class='col-xs-4 no-padding-left'>{input}</div><div class='col-xs-4 no-padding-left'>
                    <span class='btn btn-primary get_yzm'>获取验证码</span></div><div class='col-xs-8 col-xs-offset-3'>{error}</div>",
    ]) ?>
    <div class="form-group">
        <div class="col-xs-offset-4 col-xs-8 no-padding-left">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
