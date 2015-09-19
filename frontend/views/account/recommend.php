<?php
use yii\helpers\Url;
use frontend\functions\WeiXinFunctions;

$this->title = "我要推荐";

$user = Yii::$app->session->get('user');
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
        <?php
        $array = ['success','warning','info','danger'];
        ?>
        <div class="alert alert-<?=$array[rand(0,3)]?>" role="alert">
            <p><strong>号外：</strong>我有云豆在线学习的优惠推荐码（如下），使用推荐码就可以享受大额返点优惠</p>
            <p class="text-center">
                <strong>我的推荐码：<?=$user['recommendCode']?></strong>
                <button class="btn btn-xs btn-primary margin-left-10" id="copy-button" data-clipboard-text="<?=$user['recommendCode']?>" title="点击复制">复制</button>
            </p>
        </div>
        <div class="panel-body">
            <p>云豆讲堂：我们只做一件事情，<strong>让职业岗位培训学习简单再简单</strong>，我们还可以帮助企业搭建内部学习听课平台与考试平台，让企业内部学习与考试变得更简单、有效。</p>
            <div class="text-center">
                <button class="btn btn-primary" onclick="share()">点击分享，朋友们都在用，云豆手到擒来</button>
            </div>
        </div>
    </div>
</div>

<div id="share" style="display:none; position: fixed; top: 0; right: 0; text-align: right; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.498039);">
    <img src="images/share-it.png" style="position: relative;right: 10%;top: 2%">
    <p class="text-center" style="font-size: 26px;color: #fff;">分享好友或朋友圈</p>
</div>
<script>
    //分享
    var shareBox = document.getElementById("share");
    shareBox.onclick = function(e){
        shareBox.style.display = 'none';
        e.stopPropagation();
    };
    function share(){
        shareBox.style.display = "block";
    }
    //复制
    var client = new ZeroClipboard( document.getElementById("copy-button") );
    client.on( "ready", function( readyEvent ) {
        client.on( "aftercopy", function( event ) {
            event.target.innerHTML = "已复制";
        } );
    } );
</script>
