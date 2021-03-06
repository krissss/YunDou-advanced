<?php
/** @var $rightNumber int */
/** @var $wrongNumber int */

use yii\helpers\Url;
use frontend\functions\WeiXinFunctions;

$this->title = "本次练习结果";

$session = Yii::$app->session;
$user = $session->get('user');
$timestamp = time();
$currentUrl = explode('#',urldecode(Url::current([],true)))[0];
?>
<script src="./js/js-sdk.js"></script>
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
            title: '晒成绩啦！我参加‘<?=$session->get("majorJob")?>’考试，用‘云豆讲堂’进行在线练习，很不错，来试试吧！',
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

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?=$session->get('majorJob')?> | <?=$session->get('testTitle')?>
        </h3>
    </div>
    <div class="panel-body">
        <p>本次练习总题目数为：<?=$rightNumber + $wrongNumber?>题</p>
        <p>正确：<?=$rightNumber?>题</p>
        <p>错误：<?=$wrongNumber?>题</p>
        <?php
        if(($rightNumber+$wrongNumber) != 0){   //除数不能为0
            $accuracy = sprintf("%.2f", $rightNumber/($rightNumber+$wrongNumber))*100;
        }else{
            $accuracy = 0;
        }
        ?>
        <p>正确率为：<?=$accuracy?>%</p>
    </div>
    <div class="panel-footer">
        <a href="<?=Url::to(['practice/index'])?>" class="btn btn-primary">继续练习</a>
        <button class="btn btn-primary pull-right" onclick="share()">晒成绩赚云豆吧</button>
    </div>
</div>

<div id="share" style="display:none; position: fixed; top: 0; right: 0; text-align: right; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.498039);">
    <img src="images/share-it.png" style="position: relative;right: 10%;top: 2%">
    <p class="text-center" style="font-size: 26px;color: #fff;">分享朋友圈或好友，推荐还可以赚云豆哦！</p>
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
