<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TestLirarySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="test-library-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'testLibraryId') ?>

    <?= $form->field($model, 'provenceId') ?>

    <?= $form->field($model, 'testTypeId') ?>

    <?= $form->field($model, 'majorJobId') ?>

    <?= $form->field($model, 'preTypeId') ?>

    <?php // echo $form->field($model, 'testChapterId') ?>

    <?php // echo $form->field($model, 'problem') ?>

    <?php // echo $form->field($model, 'question') ?>

    <?php // echo $form->field($model, 'options') ?>

    <?php // echo $form->field($model, 'answer') ?>

    <?php // echo $form->field($model, 'analysis') ?>

    <?php // echo $form->field($model, 'picture') ?>

    <?php // echo $form->field($model, 'score') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'orderNo') ?>

    <?php // echo $form->field($model, 'createDate') ?>

    <?php // echo $form->field($model, 'createUserId') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
