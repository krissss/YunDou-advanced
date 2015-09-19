<?php
/** @var $user \common\models\Users */

use yii\helpers\Url;
use frontend\functions\WeiXinFunctions;

$this->title = $user['nickname']."的分享";

$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="./js/js-sdk.js"></script>
<script src="./js/zeroclipboard/ZeroClipboard.min.js"></script>
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
            desc:'云豆讲堂分享',
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
                    <p>关注微信公众号<strong>云豆在线学习</strong>，就可以了解权威8大员（13大员）考试信息，还可以<strong>免费模拟考试</strong>、<strong>在线试题练习</strong>、<strong>代办报名</strong>等，与正式考试同步的试题库、智能的定制学习方法，帮助您顺利通过考试。</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            <p><strong>号外：</strong>我有云豆在线学习的优惠推荐码（如下），使用推荐码就可以享受大额返点优惠</p>
            <p class="text-center">
                <strong><?=$user['nickname']?>的推荐码：<?=$user['recommendCode']?></strong>
                <button class="btn btn-xs btn-primary margin-left-10" id="copy-button" data-clipboard-text="<?=$user['recommendCode']?>" title="点击复制">复制</button>
            </p>
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
<script>
    //复制
    var client = new ZeroClipboard( document.getElementById("copy-button") );
    client.on( "ready", function( readyEvent ) {
        client.on( "aftercopy", function( event ) {
            event.target.innerHTML = "已复制";
        } );
    } );
</script>