<?php
/* @var $this yii\web\View */
/* @var $applyMoneyForm \backend\modules\associate\models\forms\ApplyMoneyForm */
/* @var $pages */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Scheme;
use common\models\Users;

$this->title = '申请提现';
$this->params['breadcrumbs'] = [
    $this->title
];

$user = Yii::$app->session->get('user');
$schemes = Scheme::findWithdrawScheme($user['role']);
if($user['role'] == Users::ROLE_AA){
    $scheme = $schemes[0];
    $tooltipTitle= "如何计算：<br>提现比例（云豆:元）：".$scheme['payBitcoin'].":".intval($scheme['getMoney'])."<br>我们将根据您的云豆余额计算您最大可提现的金额，最终扣除的云豆数如果有小数，将采取向上取整的方式扣除。";
}elseif($user['role'] == Users::ROLE_AAA){
    $scheme_low = $schemes[0];
    $scheme_high = $schemes[1];
    $tooltipTitle= "如何计算：<br>提现比例（云豆:元）：<br>累计云豆".(intval($scheme_high['totalBitcoin'])/10000)."万以内：".$scheme_low['payBitcoin'].":".intval($scheme_low['getMoney'])."<br>累计云豆".(intval($scheme_high['totalBitcoin'])/10000)."万以上：".$scheme_high['payBitcoin'].":".intval($scheme_high['getMoney'])."<br>我们将根据您的云豆余额计算您最大可提现的金额，最终扣除的云豆数如果有小数，将采取向上取整的方式扣除。";
}else{
    $tooltipTitle= "";
}
?>
<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <h4>申请提现</h4>
        <div class="form-title"></div>
        <?php $form = ActiveForm::begin([
            'method' => 'post',
            'options' => ['class'=>'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-md-5\">{input}</div>\n<div class=\"col-md-5\">{error}</div>",
                'labelOptions' => ['class' => 'col-md-2 control-label margin-bottom-20'],
            ],
        ]); ?>
        <div class="form-group field-users-nickname">
            <label class="col-md-2 control-label margin-bottom-20">提现人</label>
            <div class="col-md-5">
                <input type="text" class="form-control" value="<?=$user['nickname']?>" readonly="readonly">
            </div>
            <div class="col-md-5"><div class="help-block"></div></div>
        </div>
        <?= $form->field($applyMoneyForm, 'maxMoney',[
            'template'=>"{label}\n<div class=\"col-md-5\"><span class=\"input-icon icon-right\">{input}<i class=\"fa fa-question-circle\"
            data-toggle=\"tooltip\" data-placement=\"right\" data-container=\"body\" title=\"$tooltipTitle\"></i></span></div>\n<div class=\"col-md-5\">{error}</div>"
        ])->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($applyMoneyForm, 'money')->textInput(['type'=>'number']) ?>
        <?= $form->field($applyMoneyForm, 'invoiceMoney')->textInput(['type'=>'number']) ?>
        <?= $form->field($applyMoneyForm, 'invoiceNo')->textInput() ?>
        <div class="form-group">
            <div class="col-md-2 col-md-offset-2">
                <?= Html::submitButton('申请', ['class' => 'btn btn-primary']) ?>
                <div class="clearfix"></div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

