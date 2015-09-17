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
    <?= $form->field($updateInfoForm, 'provinceId')->dropDownList(ArrayHelper::map($provinces,'provinceId','name'),['prompt'=>'请选择','class'=>'form-control province_select'])?>
    <div class="form-group no-margin-bottom field-updateinfoform-majorjobid required has-success">
        <label class="col-xs-3 control-label" for="updateinfoform-majorjobid">专业类型</label>
        <div class="col-xs-9 no-padding-left">
            <select id="updateinfoform-majorjobid" class="form-control major_select" name="UpdateInfoForm[majorJobId]">
                <option value="">请选择</option>
                <?php foreach($majorJobs as $majorJob):?>
                <option value="<?=$majorJob['majorJobId']?>" class="province_major province_<?=$majorJob['provinceId']?>"
                    <?=$updateInfoForm['majorJobId']==$majorJob['majorJobId']?"selected=''":""  //用户已有选择则显示选中?>
                    <?=$updateInfoForm['provinceId']!=$majorJob['provinceId']?"style='display:none'":"" //省份不是用户选择的省份时隐藏?>
                    ><?=$majorJob['name']?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-xs-9 col-xs-offset-3"><div class="help-block"></div></div>
    </div>
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
