<?php
/** 练习页面 */
/** @var $this yii\web\View */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $questionNumber int */

$this->registerJsFile('frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
$session = Yii::$app->session;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?=$session->get('majorJob')?> | <?=$session->get('testTitle')?>
            （<span class="current_number"><?=$questionNumber?></span>/<span class="total_number"><?=$session->get('totalNumber')?></span>）
        </h3>
    </div>
    <?php foreach($testLibraries as $testLibrary): ?>
        <div class="test_library my_hide">
            <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$questionNumber])?>
            <?php $questionNumber++;?>
        </div>
    <?php endforeach; ?>
</div>