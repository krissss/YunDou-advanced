<?php
use yii\helpers\Url;

$this->title = "我要推荐";

$user = Yii::$app->session->get('user');
?>

<?php
use frontend\functions\WeiXinFunctions;
$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: true,
        appId: '<?=WeiXinFunctions::getAppId()?>',
        timestamp: <?=$timestamp?>,
        nonceStr: 'yundou-js',
        signature: '<?=WeiXinFunctions::generateJsSignature($currentUrl,$timestamp)?>',
        jsApiList: [
            'onMenuShareTimeline',    //分享到朋友圈
        ]
    });
    wx.ready(function(){
        wx.onMenuShareTimeline({
            title: '我的推荐码：<?=$user['recommendCode']?>',
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
        <button class="btn btn-primary" onclick="share()">分享</button>
    </div>
</div>

<div id="share" style="display:none; position: fixed; top: 0; right: 0; text-align: right; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.498039);">
    <img src="images/share-it.png" style="position: relative;right: 10%;top: 2%">
    <p class="text-center" style="font-size: 26px;color: #fff;">分享好友或朋友圈</p>
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
