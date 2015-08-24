<?php
/** 练习页面 */
/** @var $this yii\web\View */
/** @var $testLibraries common\models\TestLibrary[] */
/** @var $currentNumber int */
/** @var $startNumber int */

$this->title = "在线练习";

$session = Yii::$app->session;
?>
<div class="load-container loading">
    <div class="loader">Loading...</div>
    <p>题库资源载入中，请耐心等待。。。</p>
</div>
<?php
$currentTotalNumber = count($testLibraries);    //第一次加载的题目数量
$defaultOnceNumber = Yii::$app->params['defaultOnceNumber'];    //默认每次加载的数量
$minNumber = $currentTotalNumber>=2*$defaultOnceNumber+1?$currentNumber+1-($currentTotalNumber-1)/2:0;  //第一次加载完题目后的最小值
$maxNumber = $currentTotalNumber>=2*$defaultOnceNumber+1?$currentNumber+1+($currentTotalNumber-1)/2:$currentTotalNumber;  //第一次加载完题目后的最大值
?>
<input type="hidden" name="currentNumber" value="<?=$currentNumber+1?>">
<input type="hidden" name="minNumber" value="<?=$minNumber?>">
<input type="hidden" name="maxNumber" value="<?=$maxNumber?>">
<input type="hidden" name="totalNumber" value="<?=$session->get('totalNumber')?>">
<?php $firstIndex=$currentNumber-$startNumber-1<=-1?0:$currentNumber-$startNumber ?>
<input type="hidden" name="currentTestLibraryId" value="<?=$testLibraries[$firstIndex]['testLibraryId']?>">
<?php foreach($testLibraries as $i=>$testLibrary): ?>
    <div class="test_library test_library_<?=$startNumber+$i+1?> my_hide <?=$testLibrary['testLibraryId']?>">
        <div>标准题号：<?=$testLibrary['testLibraryId']?></div>
        <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$startNumber+$i+1])?>
    </div>
<?php endforeach; ?>
