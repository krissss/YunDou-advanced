<?php
/** 练习页面 */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $questionNumber int */

$this->registerJsFile('frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
$session = Yii::$app->session;
$questionNumber = 1;
?>
<input type="hidden" name="examFlag" value="examFlag">
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?=$session->get('majorJob')?> | <?=$session->get('testTitle')?>（<span class="current_number"><?=$questionNumber?></span>/<?=$session->get('totalNumber')?>）
        </h3>
    </div>
    <?php foreach($testLibraries as $testLibrary):?>
        <div class="test_library my_hide">
            <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$questionNumber++,'examFlag'=>true])?>
        </div>
    <?php endforeach;?>
</div>


<?php /*foreach($testLibraries as $i=>$testLibrary):*/?><!--
    <div class="allAnswers answer_<?/*=$i+1*/?>" data-answer="<?/*=$testLibrary['answer']*/?>" data-useranswer=""></div>
<?php /*endforeach;*/?>

<div id="over" class="mui-content mui-hidden">
    <div class="mui-card">
        <div class="content">
            <div class="title">恭喜您完成本次模拟考试</div>
            <div class="title">本次考试共（<span><?/*=$session->get('totalNumber')*/?></span>）题</div>
            <div class="title">正确（<span class="rightNumber"></span>）题</div>
            <div class="title">错误（<span class="wrongNumber"></span>）题</div>
            <button class="mui-btn-block mui-badge-blue">分享</button>
        </div>
    </div>
</div>-->
