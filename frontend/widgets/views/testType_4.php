<?php
/** 单选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $examFlag boolean */

$questions = explode('|',$testLibrary['question']);
$optionsAll = explode('}',$testLibrary['options']);
$answers = explode('}',$testLibrary['answer']);
?>
<?php if($examFlag):    //模拟考试隐藏题目?>
<div class="mui-hidden question_<?=$questionNumber?>">
<?php endif;?>

<!-- 题目 -->
<div class="mui-card">
    <div class="title">
        <?=$questionNumber?>.<?=$testLibrary['problem'];?>
    </div>
    <?php foreach($questions as $i=>$question):?>
        <?php $options = explode('|',$optionsAll[$i]); ?>
        <div class="title">
            <?=$i+1?>.<?=$questions[$i];?>
        </div>
        <?php if($testLibrary['picture']):?>
            <div class="picture">
                <img src="images/questions/<?=$testLibrary['picture']?>">
            </div>
        <?php endif; ?>
        <form class="mui-input-group" id="question_<?=$i+1?>" data-answer="<?=$answers[$i]?>">
            <?php foreach($options as $option): ?>
                <div class="mui-input-row mui-radio mui-left <?=substr($option,0,1)?>">
                    <label><?=$option?></label>
                    <input class="input_question_anli_<?=$questionNumber?>" name="danxuan_<?=$i+1?>" type="radio" value="<?=substr($option,0,1)?>">
                </div>
            <?php endforeach; ?>
        </form>
    <?php endforeach;?>
    <?php if(!$examFlag):    //模拟考试不需要确定按钮?>
        <a href="javascript:void(0);" class="mui-btn question-btn" id="anli-ok">确定</a>
    <?php endif;?>
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

<?php if($examFlag):    //模拟考试隐藏题目结束?>
</div>
<?php endif;?>