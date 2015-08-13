<?php


use yii\helpers\Url;
$session = Yii::$app->session;
?>

<?php
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?=$session->get('majorJob')?> | <?=$session->get('testTitle')?>
        </h3>
    </div>
    <div class="panel-body">
        <p>本次模拟考试用时为：120分钟</p>
        <p>标准考试时间为：150分钟</p>
        <p></p>
        <p>本次模拟考试分数为：60分</p>
        <p>您本次模拟考试分数在168168名模拟人员中排名在第108158位</p>
    </div>
    <div class="panel-footer">
        <a href="<?=Url::to(['exam/index'])?>" class="btn btn-primary">再模拟一次</a>
        <button class="btn btn-primary pull-right" onclick="share()">晒晒我的成绩</button>
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
