<?php
/* @var $addUserForm \backend\models\forms\AddUserForm */

use yii\widgets\ActiveForm;
?>
<div class="modal fade add_user_aaa_modal" tabindex="-1" role="dialog" aria-labelledby="添加AAA级伙伴">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加AAA级伙伴</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['user-aaa/generate'])
            ]); ?>
            <div class="modal-body">
                <?= $form->field($addUserForm, 'userId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addUserForm, 'role',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addUserForm, 'roleName')->textInput(['disabled'=>'disabled']) ?>
                <?= $form->field($addUserForm, 'username') ?>
                <?= $form->field($addUserForm, 'nickname') ?>
                <?= $form->field($addUserForm, 'address') ?>
                <?= $form->field($addUserForm, 'realname') ?>
                <?= $form->field($addUserForm, 'cellphone')->input('number') ?>
                <?= $form->field($addUserForm, 'email') ?>
                <?= $form->field($addUserForm, 'qq')->input('number') ?>
                <?= $form->field($addUserForm, 'weixin') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
