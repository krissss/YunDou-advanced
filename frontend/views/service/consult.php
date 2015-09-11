<?php
/** @var $consultForm \frontend\models\forms\ConsultForm */
/** @var $ownerServices \common\models\Service[] */
/** @var $publishServices \common\models\Service[] */

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Service;

$this->title = "咨询与建议";

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

    <?php $ownerServiceCount = count($ownerServices)?>
    <p>
        <strong>我的咨询与建议</strong>
        <?php if($ownerServiceCount == 5):  //大于5条才显示更多按钮?>
        <a class="btn btn-primary btn-sm pull-right" href="<?=Url::to(['service/self'])?>">更多</a>
        <?php endif;?>
    </p>
    <div class="clearfix"></div>
    <div class="list-group">
        <?=$ownerServiceCount==0?"无":""?>
        <?php foreach($ownerServices as $ownerService):?>
        <a href="<?=Url::to(['service/view','id'=>$ownerService['serviceId']])?>" class="list-group-item">
            <span class="badge"><?=$ownerService['state']==Service::STATE_UNREPLY?"未回复":"已回复";?></span>
            <?=$ownerService['content']?>
        </a>
        <?php endforeach;?>
    </div>

    <?php $publishServiceCount = count($publishServices)?>
    <p>
        <strong>其他咨询与建议</strong>
        <?php if($publishServiceCount == 5):  //大于5条才显示更多按钮?>
        <a class="btn btn-primary btn-sm pull-right" href="<?=Url::to(['service/other'])?>">更多</a>
        <?php endif;?>
    </p>
    <div class="clearfix"></div>
    <div class="list-group">
        <?=count($publishServices)==0?"无":""?>
        <?php foreach($publishServices as $publishService):?>
            <a href="<?=Url::to(['service/view','id'=>$publishService['serviceId']])?>" class="list-group-item"><?=$publishService['content']?></a>
        <?php endforeach;?>
    </div>
    <div class="margin-bottom-20"></div>
</div>
