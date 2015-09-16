<?php
/* @var $addPracticeForm \backend\models\forms\AddPracticeForm */

use yii\widgets\ActiveForm;
?>
<div class="modal fade add_practice_modal" tabindex="-1" role="dialog" aria-labelledby="添加或修改方案">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加或修改方案</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['practice-price/generate']),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group no-margin-bottom'],
                    'template' => "{label}<div class='col-xs-10 no-padding-left'>{input}</div><div class='col-xs-10 col-xs-offset-2'>{error}</div>",
                    'labelOptions' => ['class'=>'col-xs-2 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <?= $form->field($addPracticeForm, 'schemeId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addPracticeForm, 'name') ?>
                <?= $form->field($addPracticeForm, 'payBitcoin')->input("number") ?>
                <?= $form->field($addPracticeForm, 'hour')->input("number") ?>
                <?= $form->field($addPracticeForm, 'startDate')->input("datetime-local") ?>
                <?= $form->field($addPracticeForm, 'endDate')->input("datetime-local") ?>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
                <button type="submit" name="state" value="able" class="btn btn-primary">保存并启用</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
