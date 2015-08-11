<?php
/** 判断题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $collected boolean */

$options = explode('|',$testLibrary['options']);
$id = $testLibrary['testLibraryId'];
?>
<div class="panel-body">
    <form>
        <h4>
            <?= $questionNumber ?>.<?= $testLibrary['question']; ?>
        </h4>
        <?php foreach ($options as $option): ?>
            <?php $value = substr(trim($option), 0, 1); ?>
            <div class="form-group">
                <input id="input_<?=$id?>_<?= $value ?>" name="input_question_<?= $id ?>" type="radio" value="<?= $value ?>">
                <label for="input_<?=$id?>_<?= $value ?>"><?= $option ?></label>
            </div>
        <?php endforeach; ?>
    </form>
</div>
<div class="panel-footer">
    <div class="my_hide answer_show answer_show_<?=$id?>">
        <input type="hidden" name="answer_type_<?= $id ?>">
        <div class="answer_type_<?= $id ?>"></div>
        <div>
            正确答案：<span class="true_answer_<?= $id ?>"><?= $testLibrary['answer'] ?></span>
            &nbsp;&nbsp; 你的答案：<span class="user_answer_<?=$id?>"></span>
        </div>
    </div>
    <div class="col-xs-3">
        <button class="btn btn-primary show_answer" data-id="<?= $id ?>">确定</button>
    </div>
    <div class="col-xs-6 text-center">
        <?php $collected?$class = "btn-danger":$class = "btn-primary";?>
        <button class="btn <?=$class?> add_collection text-center" data-id="<?= $id ?>"><span class="glyphicon glyphicon-star"></span></button>
    </div>
    <div class="col-xs-3">
        <button class="btn btn-primary next_test_library pull-right" data-id="<?= $id ?>">下一题</button>
    </div>
    <div class="clearfix"></div>
</div>