<?php
/** @var $updateInfoForm \frontend\models\forms\UpdateInfoForm */
/** @var $provinces \common\models\Province[] */
/** @var $majorJobs \common\models\MajorJob[] */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Users;
use common\models\MajorJob;
use common\models\Province;

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
    <div class="form-group no-margin-bottom field-updateinfoform-provinceid required">
        <label class="col-xs-3 control-label" for="updateinfoform-provinceid">考试区域</label>
        <div class="col-xs-9 no-padding-left">
            <input type="text" id="updateinfoform-provinceid" class="form-control can_select province_input" value="<?=Province::findNameByProvinceId($updateInfoForm['provinceId'])?>" readonly="readonly" placeholder="请选择" data-toggle="modal" data-target="#provinceSelect">
            <input type="hidden" name="UpdateInfoForm[provinceId]" class="province_hidden" value="<?=$updateInfoForm['provinceId']?>">
        </div>
        <div class="col-xs-9 col-xs-offset-3"><div class="help-block"></div></div>
    </div>
    <div class="form-group no-margin-bottom field-updateinfoform-majorjobid required">
        <label class="col-xs-3 control-label" for="updateinfoform-majorjobid">专业类型</label>
        <div class="col-xs-9 no-padding-left">
            <input type="text" id="updateinfoform-provinceid" class="form-control can_select majorJob_input" value="<?=MajorJob::findNameByMajorJobId($updateInfoForm['majorJobId'])?>" readonly="readonly" placeholder="请选择" data-toggle="modal" data-target="#majorJobSelect">
            <input type="hidden" name="UpdateInfoForm[majorJobId]" class="majorJob_hidden" value="<?=$updateInfoForm['majorJobId']?>">
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

<div class="modal fade" id="provinceSelect" tabindex="-1" role="dialog" aria-labelledby="省份选择">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <?php foreach($provinces as $province): ?>
                    <?php  $isActive = $updateInfoForm['provinceId']==$province['provinceId'];  //用户已有选择则显示选中 ?>
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
                    $isActive = $updateInfoForm['majorJobId']==$majorJob['majorJobId'];  //用户已有选择则显示选中
                    $isShow = $updateInfoForm['provinceId']==$majorJob['provinceId']; //用户已有选择则显示选中
                    ?>
                    <div class="majorJob_select <?=$isShow?"pic_box_2":"pic_box_2_hide"?> <?=$isActive?"active":""?> province_<?=$majorJob['provinceId']?>" data-id="<?=$majorJob['majorJobId']?>">
                        <?=$majorJob['name']?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

