<?php
/** @var $consultForm \frontend\models\forms\ConsultForm */
/** @var $ownerServices \common\models\Service[] */
/** @var $publishServices \common\models\Service[] */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Service;
?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <?php $form = ActiveForm::begin([
        'options' => ['class' => 'form-horizontal padding-left-15 padding-right-15'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group no-margin-bottom'],
        ],
    ]) ?>
    <?= $form->field($consultForm, 'content')->textarea(['rows'=>3]) ?>
    <div class="form-group">
        <div class="no-padding-left">
            <button type="submit" class="btn btn-primary">提交</button>
        </div>
    </div>
    <?php ActiveForm::end() ?>

    <h4>我的咨询与建议</h4>
    <div class="list-group">
        <?=count($ownerServices)==0?"无":""?>
        <?php foreach($ownerServices as $ownerService):?>
        <a href="<?=Url::to(['service/view','id'=>$ownerService['serviceId']])?>" class="list-group-item">
            <span class="badge"><?=$ownerService['state']==Service::STATE_UNREPLY?"未回复":"已回复";?></span>
            <?=$ownerService['content']?>
        </a>
        <?php endforeach;?>
    </div>

    <h4>其他咨询与建议</h4>
    <div class="list-group">
        <?=count($publishServices)==0?"无":""?>
        <?php foreach($publishServices as $publishService):?>
            <a href="<?=Url::to(['service/view','id'=>$publishService['serviceId']])?>" class="list-group-item"><?=$publishService['content']?></a>
        <?php endforeach;?>
    </div>
</div>
