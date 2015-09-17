<?php
/** @var $registerForm \frontend\models\forms\RegisterForm */
/** @var $provinces \common\models\Province[] */
/** @var $majorJobs \common\models\MajorJob[] */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Province;
use common\models\MajorJob;

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
    <div class="form-group no-margin-bottom field-registerform-provinceid required">
        <label class="col-xs-3 control-label" for="registerform-provinceid">考试区域</label>
        <div class="col-xs-9 no-padding-left">
            <input type="text" id="registerform-provinceid" class="form-control can_select province_input" value="<?=Province::findNameByProvinceId($registerForm['provinceId'])?>" readonly="readonly" placeholder="请选择" data-toggle="modal" data-target="#provinceSelect">
            <input type="hidden" name="RegisterForm[provinceId]" class="province_hidden" value="<?=$registerForm['provinceId']?>">
        </div>
        <div class="col-xs-9 col-xs-offset-3"><div class="help-block"></div></div>
    </div>
    <div class="form-group no-margin-bottom field-registerform-majorjobid required">
        <label class="col-xs-3 control-label" for="registerform-majorjobid">专业类型</label>
        <div class="col-xs-9 no-padding-left">
            <input type="text" id="registerform-provinceid" class="form-control can_select majorJob_input" value="<?=MajorJob::findNameByMajorJobId($registerForm['majorJobId'])?>" readonly="readonly" placeholder="请选择" data-toggle="modal" data-target="#majorJobSelect">
            <input type="hidden" name="RegisterForm[majorJobId]" class="majorJob_hidden" value="<?=$registerForm['majorJobId']?>">
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
    <div class="checkbox col-xs-12 col-xs-offset-3 margin-bottom-20">
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

<div class="modal fade" id="provinceSelect" tabindex="-1" role="dialog" aria-labelledby="省份选择">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php foreach($provinces as $province): ?>
                    <?php  $isActive = $registerForm['provinceId']==$province['provinceId'];  //用户已有选择则显示选中 ?>
                    <div class="province_select pic_box_2 <?=$isActive?"active":""?>" data-id="<?=$province['provinceId']?>">
                        <?=$province['name']?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="majorJobSelect" tabindex="-1" role="dialog" aria-labelledby="专业岗位选择">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php foreach($majorJobs as $majorJob): ?>
                    <?php
                    $isActive = $registerForm['majorJobId']==$majorJob['majorJobId'];  //用户已有选择则显示选中
                    $isShow = $registerForm['provinceId']==$majorJob['provinceId']; //用户已有选择则显示选中
                    ?>
                    <div class="majorJob_select <?=$isShow?"pic_box_2":"pic_box_2_hide"?> <?=$isActive?"active":""?> province_<?=$majorJob['provinceId']?>" data-id="<?=$majorJob['majorJobId']?>">
                        <?=$majorJob['name']?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>