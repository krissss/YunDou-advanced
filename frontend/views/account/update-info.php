<?php
/** @var $updateInfoForm \frontend\models\forms\UpdateInfoForm */
/** @var $provinces \common\models\Province[] */
/** @var $majorJobs \common\models\MajorJob[] */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\Users;

$this->title = "更新个人信息";

$user = Yii::$app->session->get('user');
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
    <?= $form->field($updateInfoForm, 'nickname') ?>
    <?= $form->field($updateInfoForm, 'realname') ?>
    <?= $form->field($updateInfoForm, 'provinceId')->dropDownList(ArrayHelper::map($provinces,'provinceId','name'),['prompt'=>'请选择'])?>
    <?= $form->field($updateInfoForm, 'majorJobId')->dropDownList(ArrayHelper::map($majorJobs,'majorJobId','name'),['prompt'=>'请选择'])?>
    <?= $form->field($updateInfoForm, 'company') ?>
    <?= $form->field($updateInfoForm, 'address') ?>
    <div class="form-group">
        <label class="col-xs-3 control-label">手机号</label>
        <div class='col-xs-5 no-padding-left'>
            <input type="text" class="form-control" name="cellphone" value="<?=$user['cellphone']?>" disabled>
        </div>
        <div class='col-xs-4 no-padding-left'>
            <a href="<?=Url::to(['account/update-cellphone'])?>" class='btn btn-primary'>修改手机号</a>
        </div>
    </div>
    <div class="form-group">
        <label class="col-xs-3 control-label">推荐人</label>
        <div class='col-xs-9 no-padding-left'>
            <input type="text" class="form-control" name="cellphone" value="<?=Users::findRecommendUserName($user['recommendUserID'])?>" disabled>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
