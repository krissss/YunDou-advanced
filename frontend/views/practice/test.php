<?php
/**
 * Created by PhpStorm.
 * User: a8652
 * Date: 2015/7/7
 * Time: 15:45
 */
$options = explode('|',$testLibrary['options']);
?>
<!-- 头部 -->
<header class="mui-bar mui-bar-nav">
    <a class="mui-action-back mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title">在线练习-单选题</h1>
</header>
<div class="mui-content">
    <div class="mui-card">
        <div class="title">
            施工员，土建专业练习（1/300）
        </div>
    </div>
    <!-- 题目 -->
    <div class="mui-card">
        <div class="title">
            1.<?=$testLibrary['question'];?>
        </div>
        <?php if($testLibrary['picture']):?>
        <div class="picture">
            <img src="images/questions/<?=$testLibrary['picture']?>">
        </div>
        <?php endif; ?>
        <form class="mui-input-group">
            <?php foreach($options as $option): ?>
            <div class="mui-input-row mui-radio mui-left" id="<?=substr($option,0,1)?>">
                <label><?=$option?></label>
                <input name="radio1" type="radio" value="<?=substr($option,0,1)?>">
            </div>
            <?php endforeach; ?>
           <!-- <div class="mui-input-row mui-radio mui-left wrong">
                <label>B.尺寸界限、尺寸线、尺寸起止符号和尺寸数字</label>
                <input name="radio1" type="radio" value="B">
            </div>
            <div class="mui-input-row mui-radio mui-left right">
                <label>C.尺寸界限、尺寸线、尺寸数字和单位</label>
                <input name="radio1" type="radio" value="C">
            </div>
            <div class="mui-input-row mui-radio mui-left">
                <label>D.尺寸线、起止符号、箭头和尺寸数字</label>
                <input name="radio1" type="radio" value="D">
            </div>-->
        </form>
    </div>
    <!-- 解析 -->
    <div class="mui-card" id="analysis">
        <div class="title">
            解析
        </div>
        <div class="content">
            <?=$testLibrary['analysis'];?>
        </div>
    </div>
    <!-- 翻页 -->
    <div class="pagination">
        <ul class="mui-pager">
            <li class="mui-previous">
                <a href="#">上一题</a>
            </li>
            <li class="mui-next">
                <a href="#">下一题</a>
            </li>
        </ul>
    </div>
    <div id="answer" data-answer="<?=$testLibrary['answer']?>"></div>
</div>