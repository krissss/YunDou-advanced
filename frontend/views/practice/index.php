<?php

use yii\helpers\Url;
?>

<div class="panel panel-info">
    <div class="panel-heading">顺序练习</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a href="<?=Url::to(['practice/normal','type'=>'continue'])?>">
                <span class="glyphicon glyphicon-play"></span>
                <span>继续上次</span>
            </a>
        </div>
        <div class="nav-icon">
            <a href="#" data-href="<?=Url::to(['practice/normal','type'=>'restart'])?>" onclick="confirm_click(this)">
                <span class="glyphicon glyphicon-refresh"></span>
                <span>重新开始</span>
            </a>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">专项练习</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a href="<?=Url::to(['practice/single','type'=>'danxuan'])?>">
                <span class="glyphicon glyphicon-record"></span>
                <span>单选题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a href="<?=Url::to(['practice/single','type'=>'duoxuan'])?>">
                <span class="glyphicon glyphicon-check"></span>
                <span>多选题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a href="<?=Url::to(['practice/single','type'=>'panduan'])?>">
                <span class="glyphicon glyphicon-ok-circle"></span>
                <span>判断题</span>
            </a>
        </div>
        <div class="nav-icon">
            <a href="<?=Url::to(['practice/single','type'=>'anli'])?>">
                <span class="glyphicon glyphicon-edit"></span>
                <span>案例计算题</span>
            </a>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading">错题练习</div>
    <div class="panel-body">
        <div class="nav-icon">
            <a href="">
                <span class="glyphicon glyphicon-bell"></span>
                <span>所有</span>
            </a>
        </div>
    </div>
</div>
<script>
    function confirm_click(obj){
        var r = confirm("重新开始将重置顺序练习进度");
        if(r == true){
            window.location = obj.getAttribute('data-href');
        }
        return false;
    }
</script>