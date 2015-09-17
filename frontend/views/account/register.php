<?php
/** @var $registerForm \frontend\models\forms\RegisterForm */
/** @var $provinces \common\models\Province[] */
/** @var $majorJobs \common\models\MajorJob[] */

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = "实名认证";

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
    <?= $form->field($registerForm, 'nickname') ?>
    <?= $form->field($registerForm, 'realname') ?>
    <?= $form->field($registerForm, 'provinceId')->dropDownList(ArrayHelper::map($provinces,'provinceId','name'),['prompt'=>'请选择','class'=>'form-control province_select'])?>
    <div class="form-group no-margin-bottom field-updateinfoform-majorjobid required has-success">
        <label class="col-xs-3 control-label" for="updateinfoform-majorjobid">专业类型</label>
        <div class="col-xs-9 no-padding-left">
            <select id="updateinfoform-majorjobid" class="form-control major_select" name="UpdateInfoForm[majorJobId]">
                <option value="">请选择</option>
                <?php foreach($majorJobs as $majorJob):?>
                    <option value="<?=$majorJob['majorJobId']?>" class="province_major province_<?=$majorJob['provinceId']?>"
                        <?=$registerForm['majorJobId']==$majorJob['majorJobId']?"selected=''":""  //用户已有选择则显示选中?>
                        <?=$registerForm['provinceId']!=$majorJob['provinceId']?"style='display:none'":"" //省份不是用户选择的省份时隐藏?>
                        ><?=$majorJob['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-xs-9 col-xs-offset-3"><div class="help-block"></div></div>
    </div>
    <?= $form->field($registerForm, 'cellphone')->textInput(['class'=>'form-control mobile','type'=>'number']) ?>
    <?= $form->field($registerForm, 'yzm', [
        'template' => "{label}<div class='col-xs-4 no-padding-left'>{input}</div><div class='col-xs-5 no-padding-left'>
                    <span class='btn btn-primary get_yzm'>获取验证码</span></div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
    ]) ?>
    <?= $form->field($registerForm, 'company') ?>
    <?= $form->field($registerForm, 'address') ?>
    <?= $form->field($registerForm, 'tjm',[
        'template' => "{label}<div class='col-xs-4 no-padding-left'>{input}</div><div class='col-xs-5 no-padding-left'>
                    <span class='btn btn-primary validate_recommend'>推荐人检查</span></div><div class='col-xs-9 col-xs-offset-3'>{error}</div>",
    ]) ?>
    <div class="checkbox col-xs-2 col-xs-offset-3 margin-bottom-20">
        <label>
            <input type="checkbox" name="agreement" checked> <a href="<?=Url::to(['account/agreement'])?>">我同意云豆讲堂服务协议</a>
        </label>
    </div>
    <div class="form-group">
        <div class="col-xs-offset-3 col-xs-9 no-padding-left">
            <button type="button" name="" class="registerSubmit btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>
