<?php
/** @var $rightNumber int */
/** @var $wrongNumber int */

use yii\helpers\Url;

$this->title = "本次练习结果";

$session = Yii::$app->session;
$user = $session->get('user');
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
            title: '晒成绩啦！我计划参加‘<?=$session->get("majorJob")?>’考试，用‘云豆讲堂’进行在线练习，学习效率很高，也很方便，快来看看吧！',
            link: '<?=Url::base(true)?>/?r=share&userId=<?=$user['userId']?>',
            imgUrl: '<?=Url::base(true)?>/images/logo.png',
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
        <button class="btn btn-primary pull-right" onclick="share()">晒晒我的成绩</button>
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
