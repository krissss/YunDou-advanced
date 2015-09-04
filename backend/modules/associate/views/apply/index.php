<?php
/* @var $this yii\web\View */
/* @var $applyMoneyForm \backend\modules\associate\models\forms\ApplyMoneyForm */
/* @var $pages */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '申请提现';
?>
<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'options' => ['class' => ''],
            'fieldConfig' => [
                'template' => "{input}{error}",
            ],
        ]) ?>
        <table class="table table_four_column">
            <tbody>
            <tr>
                <td class="text-align-right table_bg_grey">提现人</td>
                <td>  <?= $form->field($user, 'nickname')->textInput(['readonly'=>'readonly']) ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">剩余云豆</td>
                <td> <?= $form->field($applyMoneyForm, 'maxBitcoin')->textInput(['readonly'=>'readonly']) ?></td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">提现比例</td>
                <td> 100 : 1 (云豆：元)</td>
            </tr>
            <tr>
                <td class="text-align-right table_bg_grey">提现金额</td>
                <td><?= $form->field($applyMoneyForm, 'money')->textInput(['name'=>'money','placeholder'=>'请输入提现金额']) ?></td>
            </tr>
            </tbody>
        </table>
        <div class="form-group ">
            <?= Html::submitButton('申请', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end();?>
    </div>
</div>

