<?php

//\frontend\assets\WeChatJsAsset::register($this);
use frontend\functions\WeiXinFunctions;
use yii\helpers\Url;
//$this->registerJsFile('frontend/web/js/yundou-wechat.js',['depends'=>['frontend\assets\AppAsset']]);

$user = Yii::$app->session->get('user');

$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=WeiXinFunctions::getAppId()?>', // 必填，公众号的唯一标识
        timestamp: <?=$timestamp?>, // 必填，生成签名的时间戳
        nonceStr: 'yundou-js', // 必填，生成签名的随机串
        signature: '<?=WeiXinFunctions::generateJsSignature($currentUrl,$timestamp)?>',// 必填，签名，见附录1
        jsApiList: [
            'onMenuShareTimeline',    //分享到朋友圈
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title: '<?=$user['nickname']?>的推荐码：<?=$user['recommendCode']?>',
            link: 'http://baidu.com',
            imgUrl: 'http://img3.douban.com/view/movie_poster_cover/spst/public/p2166127561.jpg',
            success: function (res) {
                alert('分享成功');
            },
            cancel: function (res) {
                alert('已取消分享');
            }
        });
    });
</script>

<div class="alert alert-info" role="alert">
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
</div>
<div class="container-fluid">
    <div class="text-center">
        <span class="recommend_code"><strong>我的推荐码:</strong><?=$user['recommendCode']?></span>
        <button class="btn btn-primary margin-left-10" data-code="1438857749W6a1p">复制</button>
    </div>
    <div class="text-center">
        <button class="btn btn-primary share" onclick="share()">分享</button>
    </div>
</div>

<div id="share" style="display:none; position: fixed; top: 0; right: 0; text-align: right; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.498039);">
    <img src="images/share-it.png" style="position: relative;right: 10%;top: 2%">
    <p class="text-center" style="font-size: 30px;color: #fff;">分享好友或朋友圈</p>
</div>
<script>
    var shareBox = document.getElementById("share");
    shareBox.onclick = function(e){
        shareBox.style.display = 'none';
        e.stopPropagation();
    };
    function share(){
        shareBox.style.display = "block";
    }
</script>
