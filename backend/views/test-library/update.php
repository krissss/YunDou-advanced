<?php
/* @var $updateTestLibraryForm \backend\models\forms\UpdateTestLibraryForm */

use yii\widgets\ActiveForm;
?>
<div class="modal fade update_testLibrary_modal" tabindex="-1" role="dialog" aria-labelledby="编辑">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">编辑题目</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['test-library/generate'])
            ]); ?>
            <div class="modal-body">
                <?= $form->field($updateTestLibraryForm, 'testLibraryId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($updateTestLibraryForm, 'problem')->textarea(['rows'=>5]) ?>
                <?= $form->field($updateTestLibraryForm, 'question')->textarea(['rows'=>5]) ?>
                <?= $form->field($updateTestLibraryForm, 'options')->textarea(['rows'=>5]) ?>
                <?= $form->field($updateTestLibraryForm, 'answer') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
