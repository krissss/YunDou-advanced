<?php
/** 练习页面 */
/** @var $this yii\web\View */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $currentNumber int */

$this->registerJsFile('frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
$session = Yii::$app->session;
?>
<input type="hidden" name="currentNumber" value="<?=$currentNumber?>">
<input type="hidden" name="currentTestLibraryId" value="<?=$testLibraries[$currentNumber]['testLibraryId']?>">
<?php foreach($testLibraries as $i=>$testLibrary): ?>
    <div class="test_library my_hide">
        <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$i+1])?>
    </div>
<?php endforeach; ?>
