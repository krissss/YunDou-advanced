<?php
/** @var $registerForm \frontend\models\forms\RegisterForm */
/** @var $provinces \common\models\Province[] */
/** @var $majorJobs \common\models\MajorJob[] */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$this->registerJsFile('frontend/web/js/yundou-register.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-register.js',['depends'=>['frontend\assets\AppAsset']]);

$session = Yii::$app->session;
$user = $session->get('user');
$openId = $session->get('openId');
?>
<?=\common\widgets\AlertWidget::widget();?>
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
    <?= $form->field($registerForm, 'nickname')->textInput(['value'=>$user['nickname']]) ?>
    <?= $form->field($registerForm, 'realname') ?>
    <?= $form->field($registerForm, 'provinceId')->dropDownList(ArrayHelper::map($provinces,'provinceId','name'),['prompt'=>'请选择'])?>
    <?= $form->field($registerForm, 'majorJobId')->dropDownList(ArrayHelper::map($majorJobs,'majorJobId','name'),['prompt'=>'请选择'])?>
    <?= $form->field($registerForm, 'cellphone')->textInput(['class'=>'form-control mobile']) ?>
    <?= $form->field($registerForm, 'yzm', [
        'template' => "{label}<div class='col-xs-4 no-padding-left'>{input}</div><div class='col-xs-5 no-padding-left'>
                    <span class='btn btn-primary get_yzm'>获取验证码</span></div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
        'labelOptions' => ['class'=>'col-xs-3 control-label'],
    ]) ?>
    <?= $form->field($registerForm, 'company') ?>
    <?= $form->field($registerForm, 'address') ?>
    <?= $form->field($registerForm, 'tjm') ?>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
