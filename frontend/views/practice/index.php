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
            <a href="<?=Url::to(['test','type'=>PPC::TYPE_SEQUENCE,'start'=>PPC::START_CONTINUE])?>">
                <span class="mui-icon mui-icon-home"></span>
                <div class="mui-media-body">继续上次</div>
            </a>
        </li>
        <li class="mui-table-view-cell mui-media mui-col-xs-4 mui-col-sm-3">
            <a href="#">
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
    <!--<div class="mui-card">
        <ul class="mui-table-view mui-table-view-chevron">
            <li class="mui-table-view-cell">
                请选择练习方式
            </li>
            <li class="mui-table-view-cell mui-collapse"><a class="mui-navigate-right" href="javascript:void(0)">顺序练习</a>
                <ul class="mui-table-view mui-table-view-chevron">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SEQUENCE,'start'=>PPC::START_CONTINUE])*/?>">继续上次</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SEQUENCE,'start'=>PPC::START_RESTART])*/?>">重新开始</a>
                    </li>
                </ul>
            </li>
            <li class="mui-table-view-cell mui-collapse"><a class="mui-navigate-right" href="javascript:void(0)">随机练习</a>
                <ul class="mui-table-view mui-table-view-chevron">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_RANDOM,'start'=>PPC::START_UNDO])*/?>">未作题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_RANDOM,'start'=>PPC::START_TOTAL])*/?>">全部题</a>
                    </li>
                </ul>
            </li>
            <li class="mui-table-view-cell mui-collapse"><a class="mui-navigate-right" href="javascript:void(0)">专项练习</a>
                <ul class="mui-table-view mui-table-view-chevron">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SPECIAL,'start'=>PPC::START_DANXUAN])*/?>">单选题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SPECIAL,'start'=>PPC::START_DUOXUAN])*/?>">多选题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SPECIAL,'start'=>PPC::START_PANDUAN])*/?>">判断题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_SPECIAL,'start'=>PPC::START_ANLI])*/?>">案例题</a>
                    </li>
                </ul>
            </li>
            <li class="mui-table-view-cell mui-collapse"><a class="mui-navigate-right" href="javascript:void(0)">错题练习</a>
                <ul class="mui-table-view mui-table-view-chevron">
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_WRONG,'start'=>PPC::START_TOTAL])*/?>">所有题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_WRONG,'start'=>PPC::START_DANXUAN])*/?>">单选题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_WRONG,'start'=>PPC::START_DUOXUAN])*/?>">多选题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_WRONG,'start'=>PPC::START_PANDUAN])*/?>">判断题</a>
                    </li>
                    <li class="mui-table-view-cell">
                        <a class="mui-navigate-right" href="<?/*=Url::to(['test','type'=>PPC::TYPE_WRONG,'start'=>PPC::START_ANLI])*/?>">案例题</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>-->
</div>