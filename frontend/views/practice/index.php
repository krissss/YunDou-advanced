<?php

use yii\helpers\Url;
use frontend\Classes\PracticeParamClass as PPC;
?>
<div class="mui-content">
    <div class="title">
        顺序练习
    </div>
    <ul class="mui-table-view mui-grid-view mui-grid-9">
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="<?=Url::to(['practice/normal','type'=>'continue'])?>">
                <span class="mui-icon mui-icon-home"></span>
                <div class="mui-media-body">继续上次</div>
            </a>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="javascript:void(0);" data-href="<?=Url::to(['practice/normal','type'=>'restart'])?>" onclick="confirm_click(this)">
                <span class="mui-icon mui-icon-email"></span>
                <div class="mui-media-body">重新开始</div>
            </a>
        </li>
    </ul>
    <div class="title">
        专项练习
    </div>
    <ul class="mui-table-view mui-grid-view mui-grid-9">
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="<?=Url::to(['practice/single','type'=>'danxuan'])?>">
                <span class="mui-icon mui-icon-home"></span>
                <div class="mui-media-body">单选题</div>
            </a>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="<?=Url::to(['practice/single','type'=>'duoxuan'])?>">
                <span class="mui-icon mui-icon-email"></span>
                <div class="mui-media-body">多选题</div>
            </a>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="<?=Url::to(['practice/single','type'=>'panduan'])?>">
                <span class="mui-icon mui-icon-email"></span>
                <div class="mui-media-body">判断题</div>
            </a>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="<?=Url::to(['practice/single','type'=>'anli'])?>">
                <span class="mui-icon mui-icon-email"></span>
                <div class="mui-media-body">案例计算题</div>
            </a>
        </li>
    </ul>
</div>
<script>
    function confirm_click(obj){
        var r=confirm("重新开始将重置顺序练习进度");
        if (r==true)
        {
            window.location.href = $(obj).data("href");
        }
    }
</script>