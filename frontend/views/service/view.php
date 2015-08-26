<?php
/** @var $service \common\models\Service */
/** @var $ownerServices \common\models\Service[] */
/** @var $publishServices \common\models\Service[] */

use yii\helpers\Url;
use common\models\Service;

$this->title = "咨询与建议";

?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <div class="head-margin-20"></div>
    <div class="panel panel-default">
        <div class="panel-body ">
            <p>咨询者：<?=$service->createUser['nickname']?></p>
            <p>咨询时间：<?=$service->createDate?></p>
            <p>咨询内容：<?=$service->content?></p>
            <p>回复内容：<?=$service->reply?></p>
            <p>回复时间：<?=$service->replyDate?></p>
            <p>回复人：<?=$service->replyUser['nickname']?></p>
        </div>
    </div>

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
