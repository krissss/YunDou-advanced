<?php

use yii\helpers\Url;

//$this->registerJsFile('frontend/web/js/yundou-practice-index.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-practice-index.js',['depends'=>['frontend\assets\AppAsset']]);

$session = Yii::$app->session;
$leftBitcoin = $session->getFlash('leftBitcoin');
$payBitcoin = $session->getFlash('payBitcoin');

?>
<div class="load-container loading" style="display: none;">
    <div class="loader">Loading...</div>
    <p>题库资源载入中，请耐心等待。。。</p>
</div>
<div class="panel panel-info">
    <div class="panel-heading">顺序练习</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/normal','type'=>'continue'])?>">
                <span class="glyphicon glyphicon-play"></span>
                <span>继续上次</span>
            </a>
        </div>
        <div class="nav-icon">
            <a class="show_modal_restart" href="#" data-href="<?=Url::to(['practice/normal','type'=>'restart'])?>">
                <span class="glyphicon glyphicon-refresh"></span>
                <span>重新开始</span>
            </a>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">专项练习</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/single','type'=>'danxuan'])?>">
                <span class="glyphicon glyphicon-record"></span>
                <span>单选题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/single','type'=>'duoxuan'])?>">
                <span class="glyphicon glyphicon-check"></span>
                <span>多选题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/single','type'=>'panduan'])?>">
                <span class="glyphicon glyphicon-ok-circle"></span>
                <span>判断题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/single','type'=>'anli'])?>">
                <span class="glyphicon glyphicon-edit"></span>
                <span>案例计算题</span>
            </a>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">错题与重点题</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/wrong-test'])?>">
                <span class="glyphicon glyphicon-remove"></span>
                <span>错题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a class="show_modal" href="#" data-href="<?=Url::to(['practice/collection-test'])?>">
                <span class="glyphicon glyphicon-star"></span>
                <span>重点题</span>
            </a>
        </div>
    </div>
</div>

<?php if($leftBitcoin || $payBitcoin):?>
<div class="modal fade" id="pay_modal" tabindex="-1" role="dialog" aria-labelledby="在线学习操作说明">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">在线学习操作说明</h4>
            </div>
            <div class="modal-body">
                <p>您还剩余“云豆”<?=$leftBitcoin?>，本次学习将使用<?=$payBitcoin?>颗。</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary pay_click">确定</button>
                <button type="button" class="btn btn-primary pay_redirect" data-href="<?=Url::to(['account/recharge'])?>">去充值</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
