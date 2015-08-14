<?php
/** 多选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $collected boolean */
/** @var $examFlag boolean */
$session = Yii::$app->session;
$options = explode('|',$testLibrary['options']);
$id = $testLibrary['testLibraryId'];
$testTypeId = 2;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $session->get('majorJob') ?> | <?= $session->get('testTitle') ?>
            （<?= $questionNumber ?>/<?= $session->get('totalNumber') ?>）
        </h3>
    </div>
    <?php if(!$examFlag):   //非考试显示结束本次练习和重点?>
    <div class="panel-heading">
        <button class="btn btn-primary btn_over">结束本次练习</button>
        <?php $collected ? $class = "btn-danger" : $class = "btn-primary"; ?>
        <button class="btn <?=$class?> pull-right add_collection" data-id="<?= $id ?>">重点</button>
        <div class="clearfix"></div>
    </div>
    <?php endif; ?>
    <div class="panel-body">
        <form>
            <h4>
                <?= $questionNumber ?>.<?= $testLibrary['question']; ?>
            </h4>
            <?php foreach ($options as $option): ?>
                <?php $value = substr(trim($option), 0, 1); ?>
                <div class="form-group">
                    <input id="input_<?=$id?>_<?= $value ?>" name="input_question_<?= $id ?>" data-id="<?= $id ?>" data-testtype="<?=$testTypeId?>" type="checkbox" value="<?= $value ?>">
                    <label for="input_<?=$id?>_<?= $value ?>"><?= $option ?></label>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
    <div class="panel-footer">
        <div class="col-xs-3">
            <?php if($questionNumber != 1): //非第一题?>
                <button class="btn btn-primary previous_text_library" data-id="<?= $id ?>">上一题</button>
            <?php endif; ?>
        </div>
        <div class="col-xs-6 text-center">
            <?php if(!$examFlag):   //非考试显示答案?>
                <button class="btn btn-primary show_answer text-center" data-id="<?= $id ?>">答案</button>
            <?php endif;?>
        </div>
        <div class="col-xs-3">
            <?php if($questionNumber == $session->get('totalNumber')):  //最后一题?>
                <button class="btn btn-primary over_test_library pull-right" data-id="<?= $id ?>">完成</button>
            <?php else: ?>
                <button class="btn btn-primary next_test_library pull-right" data-id="<?= $id ?>">下一题</button>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <?php if(!$examFlag):   //非考试显示答案?>
        <div class="my_hide answer_show answer_show_<?= $id ?>">
            <div class="answer_type_<?= $id ?>"></div>
            <div>
                正确答案：<span class="true_answer_<?= $id ?>"><?= $testLibrary['answer'] ?></span>
                &nbsp;&nbsp; 你的答案：<span class="user_answer_<?= $id ?>"></span>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>