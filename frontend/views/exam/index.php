<?php
/** 练习页面 */
/** @var $testLibraries common\models\TestLibrary[] */

$this->registerJsFile('frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
//$this->registerJsFile('YunDou-advanced/frontend/web/js/yundou-testtype.js',['depends'=>['frontend\assets\AppAsset']]);
$session = Yii::$app->session;
$questionNumber = 1;
?>
<div class="load-container loading">
    <div class="loader">Loading...</div>
    <p>题库资源载入中，请耐心等待。。。</p>
</div>
<input type="hidden" name="examFlag" value="examFlag">
<?php foreach($testLibraries as $i=>$testLibrary):?>
    <div class="test_library my_hide">
        <?= \frontend\widgets\TestTypeWidget::widget(['testLibrary'=>$testLibrary,'questionNumber'=>$i+1,'examFlag'=>true])?>
    </div>
<?php endforeach;?>
