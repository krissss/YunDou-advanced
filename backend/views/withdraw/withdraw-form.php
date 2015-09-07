<?php
/* @var $updateWithdrawForm \backend\models\forms\UpdateWithdrawForm */

use yii\widgets\ActiveForm;
use common\models\Users;
?>
<div class="modal fade withdraw_btn_modal" tabindex="-1" role="dialog" aria-labelledby="提现申请回执">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">提现申请回执</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['withdraw/generate']),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group no-margin-bottom'],
                    'template' => "{label}<div class='col-xs-9 no-padding-left'>{input}</div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
                    'labelOptions' => ['class'=>'col-xs-3 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <?= $form->field($updateWithdrawForm, 'withdrawId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($updateWithdrawForm, 'state',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($updateWithdrawForm, 'nickname')->textInput(['readonly'=>'readonly']) ?>
                <?= $form->field($updateWithdrawForm, 'money')->textInput(['readonly'=>'readonly']) ?>
                <div class="form-group no-margin-bottom">
                    <label class="col-xs-3 control-label">最大可提现金额</label>
                    <div class="col-xs-9 no-padding-left">
                        <input type="text" class="form-control" name="maxMoney" value="<?=Users::findBitcoinByWithdrawId($updateWithdrawForm['withdrawId'])?>" readonly>
                    </div>
                    <div class="col-xs-9 col-xs-offset-3">
                        <div class="help-block"></div>
                    </div>
                </div>
                <?= $form->field($updateWithdrawForm, 'invoiceMoney')->textInput(['placeholder'=>'请输入发票金额']) ?>
                <?= $form->field($updateWithdrawForm, 'invoiceNo')->textInput(['placeholder'=>'请输入发票单号'])?>
                <?= $form->field($updateWithdrawForm, 'replyContent')->textInput(['placeholder'=>'请输入回复内容']) ?>
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
