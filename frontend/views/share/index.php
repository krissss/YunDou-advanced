<?php
/** @var $user \common\models\Users */

use yii\helpers\Url;
use frontend\functions\WeiXinFunctions;

$this->title = $user['nickname']."的分享";

$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: false,
        appId: '<?=WeiXinFunctions::getAppId()?>',
        timestamp: <?=$timestamp?>,
        nonceStr: 'yundou-js',
        signature: '<?=WeiXinFunctions::generateJsSignature($currentUrl,$timestamp)?>',
        jsApiList: [
            'onMenuShareTimeline',    //分享到朋友圈
            'onMenuShareAppMessage',    //发送给朋友
            'onMenuShareQQ',    //分享到QQ
            'onMenuShareWeibo',    //分享到Weibo
            'onMenuShareQZone'    //分享到 QQ 空间
        ]
    });
    wx.ready(function(){
        var json = {
            title: '我通过云豆讲堂进行在线学习和模拟考试，系统很操作很方便、题库很权威，大家一起来学习吧！',
            link: '<?=Url::base(true)?>/?r=share&userId=<?=$user['userId']?>',
            imgUrl: '<?=Url::base(true)?>/images/logo.png',
            success: function () {
                alert('分享成功');
            }
        };
        wx.onMenuShareTimeline(json);
        wx.onMenuShareAppMessage(json);
        wx.onMenuShareQQ(json);
        wx.onMenuShareWeibo(json);
        wx.onMenuShareQZone(json);
    });
</script>

<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="media">
                <div class="media-left media-middle">
                    <a href="#">
                        <img class="media-object" src="<?=Url::base(true)?>/images/share-img.png" alt="云豆讲堂" title="云豆讲堂" width="100">
                    </a>
                </div>
                <div class="media-body">
                    <p>关注微信公众号<strong>云豆讲堂</strong>，就可以了解最新8大员、13大员考试信息，还可以<strong>免费模拟考试</strong>、<strong>在线试题练习</strong>、<strong>代办报名</strong>等，与考试同步的题库、科学定制的学习方法，帮助您顺利通过考试。</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            <p><strong>号外：</strong>实名认证时填写如下推荐码，你就可以享受大额返点优惠哦！</p>
            <p class="text-center"><strong><?=$user['nickname']?>的推荐码：<?=$user['recommendCode']?></strong></p>
        </div>
        <div class="panel-footer">
            <div class="col-xs-4 col-md-2 no-padding">
                <a href="#">
                    <img class="media-object" src="<?=Url::base(true)?>/images/qrcode_258.jpg" alt="云豆讲堂" title="云豆讲堂" width="100%">
                </a>
            </div>
            <div class="col-xs-8 col-md-10">
                <h4><strong>云豆在线学习</strong></h4>
                <p><strong>微信号</strong>：yundou025</p>
                <p><strong>功能介绍</strong>：通过“云豆讲堂”，帮助您了解最新职业考试信息、在线模拟考试、在线学习与听课，提升职业能力，取得较高考试成绩。</p>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>