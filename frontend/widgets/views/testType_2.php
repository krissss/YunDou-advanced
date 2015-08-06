<?php
/** 多选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $examFlag boolean */

$options = explode('|',$testLibrary['options']);
?>
<?php if($examFlag):    //模拟考试隐藏题目?>
<div class="mui-hidden question_<?=$questionNumber?>">
<?php endif;?>

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
            <div class="mui-input-row mui-checkbox mui-left" id="<?=substr($option,0,1)?>">
                <label><?=$option?></label>
                <input class="input_question_<?=$questionNumber?>" name="duoxuan" type="checkbox" value="<?=substr($option,0,1)?>">
            </div>
        <?php endforeach; ?>
        <?php if(!$examFlag):    //模拟考试不需要确定按钮?>
            <a href="javascript:void(0);" class="mui-btn question-btn" id="duoxuan-ok">确定</a>
        <?php endif;?>
    </form>
</div>
<!-- 解析 -->
<div class="mui-card" id="analysis" style="display: none">
    <div class="title">
        解析
    </div>
    <div class="content">
        <?=$testLibrary['analysis'];?>
    </div>
</div>
<div id="testLibraryId" data-id="<?=$testLibrary['testLibraryId']?>"></div>
<div id="answer" data-answer="<?=$testLibrary['answer']?>"></div>

<?php if($examFlag):    //模拟考试隐藏题目结束?>
</div>
<?php endif;?>