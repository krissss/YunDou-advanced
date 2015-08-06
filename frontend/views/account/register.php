<?php
/** 注册页面 */
/** @var $registerForm \frontend\models\forms\RegisterForm */
/** @var $provinces string||json */
/** @var $majorJobs string||json */

use yii\widgets\ActiveForm;

$userInfo = Yii::$app->session->get('user');
?>
<div class="mui-content">
    <p class="title">为了更好的为您提供服务，请认真进行实名认证！</p>
    <?php $form = ActiveForm::begin([
        'id' => 'account-register',
        'options' => ['class' => 'mui-input-group'],
        'fieldConfig' => [
            'options' => ['class' => 'mui-input-row'],
            'template' => "{label}{input}",
            'inputOptions' => ['class' => 'mui-input-clear'],
            //'inline' => true,
        ],
    ]) ?>
    <?= $form->field($registerForm, 'nickname')->textInput(['value'=>$userInfo['nickname'],'placeholder' => '请输入昵称']) ?>
    <?= $form->field($registerForm, 'realname')->textInput(['placeholder' => '请输入真实姓名']) ?>
    <?= $form->field($registerForm, 'weixin')->textInput(['placeholder' => '请输入微信号']) ?>
    <?= $form->field($registerForm, 'cellphone')->textInput(['placeholder' => '请输入手机号']) ?>
    <?= $form->field($registerForm, 'yzm', [
        'template' => '{label}{input}<span class="input-span-btn" id="yanzhengma-btn">点击获取</span>'
    ])->textInput([
        'class' => '',
        'placeholder' => '请输入验证码'
    ]) ?>
    <?= $form->field($registerForm, 'company')->textInput(['placeholder' => '请输入工作单位']) ?>
    <?= $form->field($registerForm, 'address')->textInput(['placeholder' => '请输入家庭住址']) ?>
    <div class="mui-input-row" id="exam-area">
        <label>考试区域</label>
        <input type="text" class="mui-input-clear" placeholder="请选择考试区域" readonly="readonly" id="exam-area-input">
        <input name="RegisterForm[province]" type="hidden" id="exam-area-input-hidden" value="">
        <span class="mui-icon mui-icon-arrowdown"></span>
    </div>
    <div class="mui-input-row" id="major-type">
        <label>专业类型</label>
        <input type="text" class="mui-input-clear" placeholder="请选择考试专业类型" readonly="readonly" id="major-type-input">
        <input name="RegisterForm[majorJobId]" type="hidden" id="major-type-input-hidden" value="">
        <span class="mui-icon mui-icon-arrowdown"></span>
    </div>
    <?= $form->field($registerForm, 'tjm')->textInput(['placeholder' => '请输入推荐码']) ?>
    <div class="mui-button-row">
        <button type="submit" class="mui-btn mui-btn-primary">提交</button>
    </div>
    <?php ActiveForm::end() ?>
</div>
<script>
    (function ($) {
        mui.init();

        var exam_area = document.getElementById("exam-area");
        var exam_area_input = document.getElementById("exam-area-input");
        var exam_area_input_hidden = document.getElementById("exam-area-input-hidden");
        var exam_area_picker = new $.PopPicker();
        exam_area_picker.setData(<?=$provinces?>);
        exam_area.addEventListener("tap", function () {
            exam_area_picker.show(function (items) {
                exam_area_input.value = items[0].text;
                exam_area_input_hidden.value = items[0].value;
            });
        }, false);

        var major_type = document.getElementById("major-type");
        var major_type_input = document.getElementById("major-type-input");
        var major_type_input_hidden = document.getElementById("major-type-input-hidden");
        var major_type_picker = new $.PopPicker();
        major_type_picker.setData(<?=$majorJobs?>);
        major_type.addEventListener("tap", function () {
            major_type_picker.show(function (items) {
                major_type_input.value = items[0].text;
                major_type_input_hidden.value = items[0].value;
            });
        }, false);
    })(mui);

    $(document).ready(function () {
        var number = 0;
        $("#registerform-cellphone").keyup(function () {
            number = parseInt($(this).val());
            if (number > 10000000000 && number < 19999999999) {
                $("#yanzhengma-btn").addClass("input-span-btn-active");
            } else {
                $("#yanzhengma-btn").removeClass("input-span-btn-active");
            }
        });

        $("#yanzhengma-btn").click(function () {
            var active = $(".input-span-btn-active");
            if (active.length > 0) {
                alert()
            }
        });
    });
</script>