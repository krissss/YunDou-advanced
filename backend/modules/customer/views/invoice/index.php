<?php
/* @var $this yii\web\View */
/* @var $applyInvoiceForm \backend\modules\customer\models\forms\ApplyInvoiceForm */
/* @var $pages */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '申请发票';
$this->params['breadcrumbs'] = [
    $this->title
];
?>
<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <h4>申请发票</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'action' => ['invoice/index'],
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-3  control-label margin-bottom-20'],
            ],
        ]); ?>
        <?= $form->field($user, 'nickname')->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($applyInvoiceForm, 'remainMoney')->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($applyInvoiceForm, 'money') ?>
        <?= $form->field($applyInvoiceForm, 'address')->textarea(['rows'=>3,'placeholder'=>'请输入详细的快递地址，发票将采用到付方式快递给您'])?>
        <div class="form-group">
            <div class="col-md-1 col-md-offset-1">
                <div class="pull-right">
                    <?= Html::submitButton('申请', ['class' => 'btn btn-primary']) ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

