<?php
/** 单选题 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */
/** @var $collected boolean */
/** @var $examFlag boolean */

use common\functions\CommonFunctions;

$session = Yii::$app->session;
$questions = explode('|',$testLibrary['question']);
$optionsAll = explode('}',$testLibrary['options']);
$answers = str_replace('}',' ',$testLibrary['answer']);
$testLibraryId = $testLibrary['testLibraryId'];
$testTypeId = $testLibrary['testTypeId'];
$preTypeId = $testLibrary['preTypeId'];
$smallPictures = explode('|',$testLibrary['pictureSmall']);   //小图片数组
$bigPictures = explode('|',$testLibrary['pictureBig']);   //小图片数组
$smallPictureIndex = 0;  //图片数组下标
$bigPictureIndex = 0;  //图片数组下标
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $session->get('majorJob') ?> | <?= $session->get('testTitle') ?>
            （<?= $questionNumber ?>/<?= $session->get('totalNumber') ?>）
        </h3>
    </div>
    <?php if($examFlag):    //考试需要倒计时?>
        <div class="panel-heading">
            <h3 class="panel-title">
                <span><?=\common\models\PreType::findNameById($preTypeId)?>|<?=\common\models\TestType::findNameById($testTypeId)?></span>
                <span class="pull-right">剩余<span class="time">150</span>分钟</span>
            </h3>
            <div class="clearfix"></div>
        </div>
    <?php endif;?>
    <?php if(!$examFlag):   //非考试显示结束本次练习和重点?>
    <div class="panel-heading">
        <button class="btn btn-primary btn_over">结束本次练习</button>
        <?php $collected ? $class = "btn-danger" : $class = "btn-primary"; ?>
        <button class="btn <?=$class?> pull-right add_collection" data-id="<?= $testLibraryId ?>">重点</button>
        <div class="clearfix"></div>
    </div>
    <?php endif; ?>
    <div class="panel-body">
        <h4>
            <?php
            $problem = CommonFunctions::replaceSmallImage($testLibrary['problem'],$smallPictures,$smallPictureIndex);
            $problem = CommonFunctions::replaceBigImage($problem,$bigPictures,$bigPictureIndex);
            ?>
            <?= $questionNumber ?>.<?= $problem; ?>
        </h4>
        <?php foreach($questions as $i=>$question):?>
            <?php
            $options = explode('|',$optionsAll[$i]);
            $id = $testLibraryId."_".$i;
            ?>
            <form>
                <h5>
                    <?php
                    $question = CommonFunctions::replaceSmallImage($questions[$i],$smallPictures,$smallPictureIndex);
                    $question = CommonFunctions::replaceBigImage($question,$bigPictures,$bigPictureIndex);
                    ?>
                    <?= $i+1 ?>.<?= $question; ?>
                </h5>
                <?php foreach ($options as $option): ?>
                    <?php
                    $value = substr(trim($option), 0, 1);
                    $option = CommonFunctions::replaceSmallImage($option,$smallPictures,$smallPictureIndex);
                    ?>
                    <div class="form-group">
                        <input id="input_<?= $id ?>_<?= $value ?>" name="input_question_<?= $id ?>" type="checkbox" value="<?= $value ?>"
                               data-id="<?= $id ?>" data-testtype="<?= $testTypeId ?>" data-pretype="<?= $preTypeId ?>">
                        <label for="input_<?= $id ?>_<?= $value ?>"><?= $option ?></label>
                    </div>
                <?php endforeach; ?>
            </form>
        <?php endforeach;?>
    </div>
    <div class="panel-footer">
        <div class="col-xs-3">
            <?php if($questionNumber != 1): //非第一题?>
                <button class="btn btn-primary previous_text_library" data-id="<?= $testLibraryId ?>">上一题</button>
            <?php endif; ?>
        </div>
        <div class="col-xs-6 text-center">
            <?php if(!$examFlag):   //非考试显示答案?>
                <button class="btn btn-primary show_answer text-center" data-id="<?= $testLibraryId ?>">答案</button>
            <?php endif;?>
        </div>
        <div class="col-xs-3">
            <?php if($questionNumber == $session->get('totalNumber')):  //最后一题?>
                <button class="btn btn-primary over_test_library pull-right" data-id="<?= $testLibraryId ?>">完成</button>
            <?php else: ?>
                <button class="btn btn-primary next_test_library pull-right" data-id="<?= $testLibraryId ?>">下一题</button>
            <?php endif; ?>
        </div>
        <div class="clearfix"></div>
        <?php if(!$examFlag):   //非考试显示答案?>
        <div class="my_hide answer_show answer_show_<?= $testLibraryId ?>">
            <div class="answer_type_<?= $testLibraryId ?>"></div>
            <div>
                <p>正确答案：<span class="true_answer_<?= $testLibraryId ?>"><?= $answers ?></span></p>
                <p>你的答案：
                <span class="user_answer_<?= $testLibraryId ?>_0">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_1">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_2">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_3">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_4">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_5">&nbsp;</span>&nbsp;
                <span class="user_answer_<?= $testLibraryId ?>_6">&nbsp;</span>&nbsp;
                </p>
            </div>
        </div>
        <?php else: //考试需要隐藏的正确答案?>
            <span class="my_hide true_answer_<?= $testLibraryId ?>"><?= $answers ?></span>
        <?php endif;?>
    </div>
</div>