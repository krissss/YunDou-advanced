<?php
/** 通过ajax向页面添加的题目 */
/** @var $testLibraries */
/** @var $startNumber */
?>
<?php foreach($testLibraries as $i=>$testLibrary): ?>
    <div class="test_library test_library_<?=$startNumber+$i+1?> my_hide id_<?=$testLibrary['testLibraryId']?>">
        <div>标准题号：<?=$testLibrary['testLibraryId']?></div>
        <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$startNumber+$i+1])?>
    </div>
<?php endforeach; ?>
