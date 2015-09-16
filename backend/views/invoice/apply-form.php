<?php
/* @var $updateInvoiceForm \backend\models\forms\UpdateInvoiceForm */

use yii\widgets\ActiveForm;
?>
<div class="modal fade invoice_btn_modal" tabindex="-1" role="dialog" aria-labelledby="发票申请回执">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">发票申请回执</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['invoice/generate']),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group no-margin-bottom'],
                    'template' => "{label}<div class='col-xs-9 no-padding-left'>{input}</div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
                    'labelOptions' => ['class'=>'col-xs-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <?= $form->field($updateInvoiceForm, 'invoiceId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($updateInvoiceForm, 'nickname')->textInput(['readonly'=>'readonly']) ?>
                <?= $form->field($updateInvoiceForm, 'money')->textInput(['readonly'=>'readonly']) ?>
                <?= $form->field($updateInvoiceForm, 'replyContent') ?>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" name="type" value="refuse" class="btn btn-warning">拒绝</button>
                <button type="submit" name="type" value="agree" class="btn btn-success">同意</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
