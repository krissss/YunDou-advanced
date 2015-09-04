<?php
/** @var $formModifyPassword \backend\modules\customer\models\forms\ModifyPassWordForm */

use yii\helpers\html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '修改密码';
?>
<div class="widget flat">
    <div class="widget-body">
        <h4>修改密码</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'action' => ['default/modify-password'],
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
            <div class="col-md-1 col-md-offset-1">
                <div class="pull-right">
                        <a class="btn btn-warning" href="<?=Url::to(['default/index'])?>">返回</a>
                    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
