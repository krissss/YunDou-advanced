<?php
/** @var $updateUserForm \backend\modules\associate\models\forms\UpdateUserForm */

use yii\helpers\html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '管理银行卡';
$this->params['breadcrumbs'] = [
    [
        'label' => '我的信息',
        'url' => ['default/index'],
    ],
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <h4>管理银行卡</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'action' => ['default/update-bank'],
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-1 control-label margin-bottom-20'],
            ],
        ]); ?>
        <?= $form->field($updateUserForm, 'bankName') ?>
        <?= $form->field($updateUserForm, 'cardNumber') ?>
        <?= $form->field($updateUserForm, 'cardName') ?>
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

