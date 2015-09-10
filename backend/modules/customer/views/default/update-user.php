<?php
/** @var $updateUserForm \backend\modules\customer\models\forms\UpdateUserForm */

use yii\helpers\html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '信息更改';
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
        <h4>信息更改</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'action' => ['default/update-user'],
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-1 control-label margin-bottom-20'],
            ],
        ]); ?>
        <?= $form->field($user, 'username')->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($updateUserForm, 'nickname') ?>
        <?= $form->field($updateUserForm, 'realname') ?>
        <?= $form->field($updateUserForm, 'cellphone') ?>
        <?= $form->field($updateUserForm, 'qq') ?>
        <?= $form->field($updateUserForm, 'weixin') ?>
        <?= $form->field($updateUserForm, 'address') ?>
        <div class="form-group">
            <div class="col-md-1 col-md-offset-1">
                <div class="pull-right">
                    <a class="btn btn-warning" href="<?=Url::to(['default/index'])?>">返回</a>
                    <?= Html::submitButton('确定', ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>

