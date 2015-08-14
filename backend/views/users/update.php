<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Users */

$this->title = 'Update Users: ' . ' ' . $model->userId;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->userId, 'url' => ['view', 'userId' => $model->userId]];
$this->params['breadcrumbs'][] = '修改';
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
                <td><?= $form->field($model, 'nickname')->textInput(['readonly'=>'readonly'])  ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">密码</td>
                <td><?= $form->field($model, 'password') ?></td>
                <td class="text-align-right table_bg_grey">地址</td>
                <td><?= $form->field($model, 'address') ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">单位</td>
                <td><?= $form->field($model, 'company') ?></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group">
            <?= Html::submitButton('修改', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

