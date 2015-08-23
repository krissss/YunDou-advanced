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
            title: '我通过云豆讲堂进行在线学习和模拟考试，系统很操作很方便、题库也很不错，大家一起来学习吧！',
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
                        <img class="media-object" src="<?=Url::base(true)?>/images/logo2.png" alt="云豆讲堂" title="云豆讲堂" style="height: 50px">
                    </a>
                </div>
                <div class="media-body">
                    <p>关注‘云豆讲堂’的官方微信，平台将为您推送最新考试资讯与建筑行业权威信息，
                        同时还提供在线模拟考试、在线练习、在线听课服务，帮助您顺利完成考试，取得好成绩！</p>
                    <p>实名认证时填写我的推荐码，你就可以享受5%的云豆返点哦！</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            <p>我通过云豆讲堂进行在线学习和模拟考试，系统很操作很方便、题库也很不错，大家一起来学习吧。</p>
            <p class="text-center"><strong>我的推荐码：<?=$user['recommendCode']?></strong></p>
        </div>
        <div class="panel-body">
            <p><strong>云豆讲堂——专业的在线职业培训平台</strong></p>
            <p>权威的试题、多种学习模式，做题与娱乐两不误，动力十足。随时随地，想学就学，从此高分不是梦。</p>
            <div class="text-center">
                <button class="btn btn-primary" onclick="share()">点击分享，云豆手到擒来</button>
            </div>
        </div>
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
