<?php
/* @var $addUserForm \backend\models\forms\AddUserForm */
/* @var $departments \common\models\Department[] */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="modal fade add_user_big_modal" tabindex="-1" role="dialog" aria-labelledby="添加或修改大客户">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加或修改大客户</h4>
            </div>
            <div class="alert alert-info" role="alert">
                <p>添加用户后默认密码为：123456，请提醒用户尽快修改密码，修改信息不重置密码</p>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['user-big/generate']),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group no-margin-bottom'],
                    'template' => "{label}<div class='col-xs-10 no-padding-left'>{input}</div><div class='col-xs-10 col-xs-offset-2'>{error}</div>",
                    'labelOptions' => ['class'=>'col-xs-2 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <?= $form->field($addUserForm, 'userId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addUserForm, 'role',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addUserForm, 'roleName')->textInput(['disabled'=>'disabled']) ?>
                <?= $form->field($addUserForm, 'departmentId')->dropDownList(ArrayHelper::map($departments,'departmentId','name'),['prompt'=>'请选择']) ?>
                <?php
                if($addUserForm['userId']){ //如果不是添加新用户
                    $inputAttr = [
                        'readonly'=>'readonly'
                    ];
                }else{
                    $inputAttr = [
                        'placeholder'=>'数字和字母的组合，不区分大小写,设置后不可更改',
                    ];
                }
                ?>
                <?= $form->field($addUserForm, 'username')->textInput($inputAttr) ?>
                <?= $form->field($addUserForm, 'nickname') ?>
                <?= $form->field($addUserForm, 'address') ?>
                <?= $form->field($addUserForm, 'realname') ?>
                <?= $form->field($addUserForm, 'cellphone')->input('number') ?>
                <?= $form->field($addUserForm, 'email') ?>
                <?= $form->field($addUserForm, 'qq')->input('number') ?>
                <?= $form->field($addUserForm, 'weixin') ?>
                <?php
                if($addUserForm['userId']){ //如果不是添加新用户
                    $inputAttr = [
                        'readonly'=>'readonly',
                        'value' => $addUserForm->recommendCode?$addUserForm->recommendCode:"无"
                    ];
                    $template = "{label}<div class='col-xs-10 no-padding-left'>{input}</div><div class='col-xs-10 col-xs-offset-2'>{error}</div>";
                }else{
                    $inputAttr = [
                        'placeholder'=>'可填写伙伴推荐码',
                    ];
                    $template = "{label}<div class='col-xs-5 no-padding-left'>{input}</div><div class='col-xs-5 no-padding-left'>
                    <span class='btn btn-primary validate_recommend'>推荐人检查</span></div><div class='col-xs-9 col-xs-offset-3'>{error}</div>";
                }
                ?>
                <?= $form->field($addUserForm, 'recommendCode',[
                    'template' => $template
                ])->textInput($inputAttr) ?>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
