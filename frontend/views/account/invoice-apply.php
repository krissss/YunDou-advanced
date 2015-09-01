<?php
/** @var $applyInvoiceForm \frontend\models\forms\ApplyInvoiceForm */
/** @var $invoices \common\models\Invoice[] */
/** @var $pages */

use yii\widgets\ActiveForm;
use common\functions\DateFunctions;
use yii\helpers\Url;

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
    <p>
        <strong>发票申请记录</strong>
        <a href="<?=Url::to(['account/index'])?>" class="btn btn-primary btn-sm pull-right margin-left-10">云豆记录</a>
        <a href="<?=Url::to(['account/pay-record'])?>" class="btn btn-primary btn-sm pull-right">充值记录</a>
    </p>
    <table class="table table-striped text-center no-margin-bottom">
        <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">申请时间</th>
            <th class="text-center">金额</th>
            <th class="text-center">状态</th>
            <th class="text-center">快递单号</th>
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
    <nav class="pull-right pagination_footer">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </nav>
    <div class="clearfix"></div>

</div>


