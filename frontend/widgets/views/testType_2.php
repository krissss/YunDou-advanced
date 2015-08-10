<?php
/** 多选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $examFlag boolean */

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
                <input id="input_<?=$id?>_<?= $value ?>" name="input_question_<?= $id ?>" type="checkbox" value="<?= $value ?>">
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
    <button class="btn btn-primary show_answer" data-id="<?=$id?>">确定</button>
    <button class="btn btn-primary pull-right next_test_library" data-id="<?=$id?>">下一题</button>
</div>