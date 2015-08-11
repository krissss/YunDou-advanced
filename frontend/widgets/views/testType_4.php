<?php
/** 单选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $collected boolean */

$questions = explode('|',$testLibrary['question']);
$optionsAll = explode('}',$testLibrary['options']);
$answers = explode('}',$testLibrary['answer']);
$testLibraryId = $testLibrary['testLibraryId'];
?>
<div class="panel-body">
    <h4>
        <?= $questionNumber ?>.<?= $testLibrary['problem']; ?>
    </h4>
    <?php foreach($questions as $i=>$question):?>
        <?php
        $options = explode('|',$optionsAll[$i]);
        $id = $testLibraryId."_".$i;
        ?>
        <form>
            <h5>
                <?= $i+1 ?>.<?= $questions[$i]; ?>
            </h5>
            <?php foreach ($options as $option): ?>
                <?php $value = substr(trim($option), 0, 1); ?>
                <div class="form-group">
                    <input id="input_<?=$id?>_<?= $value ?>" name="input_question_<?= $id ?>" type="radio" value="<?= $value ?>">
                    <label for="input_<?=$id?>_<?= $value ?>"><?= $option ?></label>
                </div>
            <?php endforeach; ?>
        </form>
    <?php endforeach;?>
</div>
<div class="panel-footer">
    <div class="my_hide answer_show answer_show_<?=$testLibraryId?>">
        <input type="hidden" name="answer_type_<?= $testLibraryId ?>">
        <div class="answer_type_<?= $testLibraryId ?>"></div>
        <div>
            <input type="hidden" name="true_answer_<?= $testLibraryId ?>" value="<?= $testLibrary['answer'] ?>">
            <p>正确答案：<span class="true_answer_<?= $testLibraryId ?>"></span></p>
            <p>你的答案：<span class="user_answer_<?=$testLibraryId?>"></span></p>
        </div>
    </div>
    <div class="col-xs-3">
        <button class="btn btn-primary show_answer_anli" data-id="<?=$testLibraryId?>" data-number="<?=count($questions)?>">确定</button>
    </div>
    <div class="col-xs-6 text-center">
        <?php $collected?$class = "btn-danger":$class = "btn-primary";?>
        <button class="btn <?=$class?> add_collection text-center" data-id="<?= $testLibraryId ?>"><span class="glyphicon glyphicon-star"></span></button>
    </div>
    <div class="col-xs-3">
        <button class="btn btn-primary pull-right next_test_library" data-id="<?=$testLibraryId?>">下一题</button>
    </div>
    <div class="clearfix"></div>
</div>