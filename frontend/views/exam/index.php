<?php
/** 练习页面 */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $questionNumber int */

$session = Yii::$app->session;
$questionNumber = 1;
?>
<!-- 头部 -->
<header class="mui-bar mui-bar-nav">
    <h1 class="mui-title">
        <?=$session->get('majorJob')?>-<?=$session->get('testTitle')?>
        （<span class="currentNumber"><?=$questionNumber?></span>/<span class="totalNumber"><?=$session->get('totalNumber')?></span>）
    </h1>
</header>
<div class="mui-content">
    <input id="examFlag" type="hidden" value="exam">
    <?php foreach($testLibraries as $testLibrary):?>
    <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$questionNumber++,'examFlag'=>true])?>
    <?php endforeach;?>
    <!-- 翻页 -->
    <div class="pagination">
        <ul class="mui-pager">
            <!--<li class="mui-previous">
                <a href="#">上一题</a>
            </li>-->
            <li class="mui-next">
                <a href="javascript:void(0);" class="next_question">下一题</a>
            </li>
        </ul>
    </div>
</div>

<?php foreach($testLibraries as $i=>$testLibrary):?>
    <div class="allAnswers answer_<?=$i+1?>" data-answer="<?=$testLibrary['answer']?>" data-useranswer=""></div>
<?php endforeach;?>

<div id="over" class="mui-content mui-hidden">
    <div class="mui-card">
        <div class="content">
            <div class="title">恭喜您完成本次模拟考试</div>
            <div class="title">本次考试共（<span><?=$session->get('totalNumber')?></span>）题</div>
            <div class="title">正确（<span class="rightNumber"></span>）题</div>
            <div class="title">错误（<span class="wrongNumber"></span>）题</div>
            <button class="mui-btn-block mui-badge-blue">分享</button>
        </div>
    </div>
</div>
