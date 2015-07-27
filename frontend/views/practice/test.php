<?php
/** 练习页面 */
/** @var $testLibrary common\models\TestLibrary */
/** @var $questionNumber int */

use yii\helpers\Url;

$session = Yii::$app->session;
?>
<!-- 头部 -->
<header class="mui-bar mui-bar-nav">
    <a href="<?=Url::to(['practice/index'])?>" class="mui-icon mui-icon-left-nav mui-pull-left"></a>
    <h1 class="mui-title"><?=$session->get('testTitle')?></h1>
</header>
<div class="mui-content">
    <div class="mui-card">
        <div class="title">
            <?=$session->get('majorJob')?>练习（<?=$questionNumber?>/<?=$session->get('totalNumber')?>）
        </div>
    </div>

    <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$questionNumber])?>
    <!-- 翻页 -->
    <div class="pagination">
        <ul class="mui-pager">
            <!--<li class="mui-previous">
                <a href="#">上一题</a>
            </li>-->
            <li class="mui-next">
                <a href="<?=Url::to(['practice/single-next','next'=>$testLibrary['testLibraryId']])?>" class="next_question">下一题</a>
            </li>
        </ul>
    </div>
</div>