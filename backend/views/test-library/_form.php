<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TestLibrary */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-library-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'provenceId')->textInput() ?>

    <?= $form->field($model, 'testTypeId')->textInput() ?>

    <?= $form->field($model, 'majorJobId')->textInput() ?>

    <?= $form->field($model, 'preTypeId')->textInput() ?>

    <?= $form->field($model, 'testChapterId')->textInput() ?>

    <?= $form->field($model, 'problem')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'options')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'analysis')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'picture')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'score')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'createDate')->textInput() ?>

    <?= $form->field($model, 'createUserId')->textInput() ?>

    <?= $form->field($model, 'remark')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
