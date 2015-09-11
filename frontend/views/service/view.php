<?php
/** @var $service \common\models\Service */

$this->title = "咨询与建议详情";

?>
<?=\common\widgets\AlertWidget::widget();?>
<div class="container-fluid">
    <div class="head-margin-20"></div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <p>咨询者：<?=$service->createUser['nickname']?></p>
            <p>咨询时间：<?=$service->createDate?></p>
            <p>咨询内容：<strong><?=$service->content?></strong></p>
        </div>
        <div class="panel-body">
            <p>回复内容：<?=$service->reply?></p>
        </div>
        <div class="panel-footer">
            <p>回复时间：<?=$service->replyDate?></p>
            <p>回复人：<?=$service->replyUser['nickname']?></p>
        </div>
    </div>
    <a href="javascript:history.go(-1)" class="btn btn-primary pull-right">返回</a>
    <div class="clearfix margin-bottom-20"></div>
</div>
