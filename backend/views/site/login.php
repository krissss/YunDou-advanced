<?php
/* @var $this yii\web\View */
/* @var $model \backend\models\forms\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = "登录";
$this->registerCssFile('backend/web/css/yundou-login.css',['depends'=>'backend\assets\AppAsset']);
$this->params['breadcrumbs'] = [
    'id'=>'empty-container',
];
?>
<div class="login-container animated fadeInDown">
    <div class="loginbox bg-white">
        <div class="loginbox-title">
            <h4>登录</h4>
        </div>
        <?php $form = ActiveForm::begin([
            'fieldConfig' => [
                'template' => "{input}{error}",
            ],
        ]); ?>
        <div class="loginbox-textbox">
            <?= $form->field($model, 'username')->textInput(['placeholder'=>'用户名']) ?>
        </div>
        <div class="loginbox-textbox">
            <?= $form->field($model, 'password')->passwordInput(['placeholder'=>'密码']) ?>
        </div>
        <div class="loginbox-textbox">
            <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                'template' => '<div class="row"><div class="col-sm-6">{image}</div><div class="col-sm-6">{input}</div></div>',
            ]);?>
        </div>
        <div class="loginbox-submit">
            <input type="submit" class="btn btn-primary btn-block" value="登录">
        </div>
        <!--<div class="loginbox-forgot">
            <a href="">忘记密码</a>
        </div>

        <div class="loginbox-signup">
            <a href="<?/*=Url::to(['account/register'])*/?>">注册账户</a>
        </div>-->
        <?php ActiveForm::end(); ?>
    </div>

    <!--<div class="logobox">
    </div>-->
</div>
