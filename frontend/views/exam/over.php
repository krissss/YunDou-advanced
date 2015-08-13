<?php


use yii\helpers\Url;
$session = Yii::$app->session;
?>
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
