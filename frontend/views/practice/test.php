<?php
/** 练习页面 */
/** @var $this yii\web\View */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $currentNumber int */

$this->registerJsFile('frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
$session = Yii::$app->session;
//$user = $session->get('user');
//$testTypeId = $session->get('testTypeId');
?>
<div class="load-container loading">
    <div class="loader">Loading...</div>
    <p>题库资源载入中，请耐心等待。。。</p>
</div>
<?//php $testLibraries = \common\models\TestLibrary::findAllByUserAndTestType($user,$testTypeId);?>
<input type="hidden" name="currentNumber" value="<?=$currentNumber?>">
<input type="hidden" name="currentTestLibraryId" value="<?=$testLibraries[$currentNumber]['testLibraryId']?>">
<?php foreach($testLibraries as $i=>$testLibrary): ?>
    <?//php if ($this->beginCache($testLibrary['testLibraryId'], ['duration' => 60])) :?>
        <div class="test_library my_hide">
            <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$i+1])?>
        </div>
    <?//php $this->endCache(); endif;?>
<?php endforeach; ?>
