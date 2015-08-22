<?php
/* @var $addRechargeForm \backend\models\forms\AddRechargeForm */

use yii\widgets\ActiveForm;
?>
<div class="modal fade add_recharge_modal" tabindex="-1" role="dialog" aria-labelledby="添加或修改方案">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">添加或修改方案</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['recharge/generate'])
            ]); ?>
            <div class="modal-body">
                <?= $form->field($addRechargeForm, 'schemeId',[
                    'template' => "{input}"
                ])->hiddenInput()?>
                <?= $form->field($addRechargeForm, 'name') ?>
                <?= $form->field($addRechargeForm, 'payMoney')->input("number",['value'=>1]) ?>
                <?= $form->field($addRechargeForm, 'getBitcoin')->input("number") ?>
                <?= $form->field($addRechargeForm, 'startDate')->input("datetime-local") ?>
                <?= $form->field($addRechargeForm, 'endDate')->input("datetime-local") ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">保存</button>
                <button type="submit" name="state" value="able" class="btn btn-primary">保存并启用</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
