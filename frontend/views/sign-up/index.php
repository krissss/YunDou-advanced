<?php
/* @var $this yii\web\View */
/* @var $signUpForm frontend\models\forms\SignUpForm */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = "填写报名信息";
?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
            'class' => 'form-horizontal'
        ],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
            'template' => "{label}<div class='col-xs-8 no-padding-left'>{input}</div><div class='col-xs-8 col-xs-offset-4'>{error}</div>",
            'labelOptions' => ['class'=>'col-xs-4 control-label']
        ]
    ]); ?>

        <?= $form->field($signUpForm, 'IDCard') ?>
        <?= $form->field($signUpForm, 'realName') ?>
        <?= $form->field($signUpForm, 'cellphone') ?>
        <?= $form->field($signUpForm, 'education')->dropDownList([
            '初中'=>'初中',
            '高中'=>'高中',
            '中专'=>'中专',
            '大专' => '大专',
            '本科' => '本科',
            '硕士研究生' => '硕士研究生',
            '博士研究生' => '博士研究生',
            '博士后' => '博士后',
        ],['prompt'=>'请选择学历']) ?>
        <?= $form->field($signUpForm, 'major') ?>
        <?= $form->field($signUpForm, 'workTime')->input('date') ?>
        <?= $form->field($signUpForm, 'technical')->dropDownList([
            '无职称'=>'无职称',
            '初级'=>'初级',
            '中级'=>'中级',
            '高级' => '高级',
        ])?>
        <?= $form->field($signUpForm, 'signUpMajor') ?>
        <?= $form->field($signUpForm, 'company') ?>
        <?= $form->field($signUpForm, 'findPasswordQuestion')->dropDownList([
            '我小学的学校名称是？'=>'我小学的学校名称是？',
            '我小学班主任姓名是？'=>'我小学班主任姓名是？',
            '我的家乡是？'=>'我的家乡是？',
            '我最喜欢的电影名称是？' => '我最喜欢的电影名称是？',
            '我最喜欢的歌曲名称是？' => '我最喜欢的歌曲名称是？',
        ])  ?>
        <?= $form->field($signUpForm, 'findPasswordAnswer') ?>
        <?= $form->field($signUpForm, 'headImg')->fileInput() ?>
        <?= $form->field($signUpForm, 'educationImg')->fileInput() ?>
    
        <div class="form-group">
            <?= Html::submitButton('提交', ['class' => 'btn btn-primary col-xs-offset-4']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div>
