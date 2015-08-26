<?php
/** @var $applyInvoiceForm \frontend\models\forms\ApplyInvoiceForm */
/** @var $invoices \common\models\Invoice[] */

use yii\widgets\ActiveForm;
use common\functions\DateFunctions;

$this->title = "发票申请";

?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'id' => 'account-register',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-9 no-padding-left'>{input}</div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-3 control-label'],
        ],
    ]) ?>
    <?= $form->field($applyInvoiceForm, 'money')->textInput(['type'=>'number']) ?>
    <?= $form->field($applyInvoiceForm, 'maxMoney')->textInput(['readonly'=>'readonly']) ?>
    <?= $form->field($applyInvoiceForm, 'address') ?>
    <?= $form->field($applyInvoiceForm, 'description')->textarea(['rows'=>3]) ?>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>

    <hr>
    <h4>发票申请记录</h4>
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">申请时间</th>
            <th class="text-center">金额</th>
            <th class="text-center">状态</th>
            <th class="text-center">订单号</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($invoices as $i=>$invoice): ?>
            <tr>
                <th scope="row"><?=$i+1?></th>
                <td><?=DateFunctions::getDate($invoice['createDate'])?></td>
                <td><?=$invoice['money']?></td>
                <td><?=$invoice->stateName?></td>
                <td><?=$invoice->orderNumber?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

</div>


