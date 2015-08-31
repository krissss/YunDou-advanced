<?php
/* @var $rechargeForm \backend\models\forms\RechargeForm */
/* @var $user \common\models\Users */

use yii\widgets\ActiveForm;
?>
<div class="modal fade recharge_user_aa_modal" tabindex="-1" role="dialog" aria-labelledby="AA级伙伴充值">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">AA级伙伴充值</h4>
            </div>
            <?php $form = ActiveForm::begin([
                'action'=>\yii\helpers\Url::to(['user-aa/generate-recharge']),
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group no-margin-bottom'],
                    'template' => "{label}<div class='col-xs-10 no-padding-left'>{input}</div><div class='col-xs-10 col-xs-offset-2'>{error}</div>",
                    'labelOptions' => ['class'=>'col-xs-2 control-label'],
                ],
            ]); ?>
            <div class="modal-body">
                <div class="form-group no-margin-bottom">
                    <label class="col-xs-2 control-label">用户名称</label>
                    <div class="col-xs-10 no-padding-left">
                        <input class="form-control" name="" value="<?=$user->nickname?>" readonly>
                    </div>
                    <div class="col-xs-10 col-xs-offset-2"><div class="help-block"></div></div>
                </div>
                <div class="form-group no-margin-bottom">
                    <label class="col-xs-2 control-label">所属部门</label>
                    <div class="col-xs-10 no-padding-left">
                        <input class="form-control" name="" value="<?=$user->department['name']?>" readonly>
                    </div>
                    <div class="col-xs-10 col-xs-offset-2"><div class="help-block"></div></div>
                </div>
                <?= $form->field($rechargeForm, 'userId',[
                    'template' => "{input}"
                ])->hiddenInput(['value'=>$user->userId])?>
                <?= $form->field($rechargeForm, 'from')->dropDownList([
                    '1'=>'微信支付','2'=>'支付宝支付','3'=>'现金支付'
                ],['prompt'=>'请选择']) ?>
                <?= $form->field($rechargeForm, 'money')->input('number') ?>
                <?= $form->field($rechargeForm, 'bitcoin')->input('number') ?>
                <?= $form->field($rechargeForm, 'agreement') ?>
            </div>
            <div class="clearfix"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">确定</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
