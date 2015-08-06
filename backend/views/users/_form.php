<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'options' => ['class' => 'input-group input-group-sm'],
    ]) ?>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'cellphone')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'weixin')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'majorJobId')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'introduce')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'bitcoin')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'province')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'cityId')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'registerDate')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'role')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'recommendUserID')->textInput() ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <br>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
