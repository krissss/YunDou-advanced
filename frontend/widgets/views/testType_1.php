<?php
/** 单选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */

$options = explode('|',$testLibrary['options']);
?>
<!-- 题目 -->
<div class="mui-card">
    <div class="title">
        <?=$questionNumber?>.<?=$testLibrary['question'];?>
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
                <input name="danxuan" type="radio" value="<?=substr($option,0,1)?>">
            </div>
        <?php endforeach; ?>
    </form>
</div>
<!-- 解析 -->
<div class="mui-card" id="analysis" style="display: none">
    <div class="title" id="answer-title">
        答案正确
    </div>
    <div class="content" id="answer-content">
        正确答案：A  你的答案：A
    </div>
    <div class="title">
        解析
    </div>
    <div class="content">
        <?=$testLibrary['analysis'];?>
    </div>
</div>
<div id="testLibraryId" data-id="<?=$testLibrary['testLibraryId']?>"></div>
<div id="answer" data-answer="<?=$testLibrary['answer']?>"></div>