<?php
/** @var $formModifyPassword \backend\modules\customer\models\forms\ModifyPassWordForm */

use yii\helpers\html;
use yii\widgets\ActiveForm;

$this->title = '修改密码';
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <h4>修改密码</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'action' => ['modify-password/index'],
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\"><span class=\"input-icon icon-right\">{input}<i class=\"fa fa-lock\"></i></span></div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-1 control-label margin-bottom-20'],
            ],
        ]); ?>
        <?= $form->field($formModifyPassword, 'password')->passwordInput() ?>
        <?= $form->field($formModifyPassword, 'newPassword')->passwordInput() ?>
        <?= $form->field($formModifyPassword, 'newPasswordAgain')->passwordInput() ?>
        <div class="form-group">
            <div class="col-md-12 col-md-offset-1">
                <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
