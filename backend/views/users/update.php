<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Update Users: ' . ' ' . $model->userId;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->userId, 'url' => ['view', 'userId' => $model->userId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="widget flat">
    <div class="widget-body">
        <?php $form = \yii\widgets\ActiveForm::begin([
            'options' => ['class' => ''],
            'fieldConfig' => [
                'template' => "{input}",
            ],
        ]) ?>
        <table class="table table_four_column">
            <tbody>
            <tr>
                <td class="text-align-right table_bg_grey">微信id</td>
                <td><?= $form->field($model, 'weixin')->textInput(['readonly'=>'readonly']) ?></td>
                <td class="text-align-right table_bg_grey">昵称</td>
                <td><?= $form->field($model, 'nickname') ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">手机号</td>
                <td><?= $form->field($model, 'cellphone') ?></td>
                <td class="text-align-right table_bg_grey">性别</td>
                <td><?= $form->field($model, 'sex') ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">真实姓名</td>
                <td><?= $form->field($model, 'realname') ?></td>
                <td class="text-align-right table_bg_grey">简介</td>
                <td><?= $form->field($model, 'introduce') ?></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group">
            <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<div class="users-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
