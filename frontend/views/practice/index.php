<?php
/** 练习首页 */
use yii\helpers\Url;
use common\models\Users;

$this->title = "在线练习";

$session = Yii::$app->session;
$sessionUser = $session->get('user');
$practiceRecordFlag = $session->getFlash('practiceRecordFlag'); //用户没有练习权限的标志
$schemes = $session->getFlash('practice-schemes');   //所有的练习付款方案

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

<?php if($practiceRecordFlag && $schemes):?>
<div class="modal fade" id="pay_modal" tabindex="-1" role="dialog" aria-labelledby="在线学习支付">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">在线学习支付</h4>
            </div>
            <div class="modal-body">
                <p class="margin-bottom-20">您还剩余云豆:<strong><?=Users::findBitcoin($sessionUser['userId'])?></strong>颗</p>
                <input type="hidden" name="schemeId" value="">
                <?php foreach($schemes as $scheme): ?>
                    <div class="pic_box_3 practice_select" data-id="<?=$scheme['schemeId']?>">
                        <div class="bitcoin"><?=$scheme['payBitcoin']?><small>云豆</small></div>
                        <div class="rmb">使用<?=$scheme['hour']?>小时</div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary pay_click">确定</button>
                <button type="button" class="btn btn-primary pay_redirect" data-href="<?=Url::to(['recharge/index'])?>">去充值</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
