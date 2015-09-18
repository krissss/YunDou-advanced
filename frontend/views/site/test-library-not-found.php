<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->registerCssFile('/frontend/web/css/404.css',['depends' => \frontend\assets\AppAsset::className()]);
$this->title = "题库建设中";
?>
<div id="wrap">
    <div>
        <img src="./images/404/test_building.png" alt="<?=$this->title?>" />
    </div>
    <div id="text">
        <strong>
            <span style="color: #fff; font-size: 12px;">
                我们正在紧急准备题库中
            </span>
            <a href="<?=Url::to(['site/index'])?>">返回首页</a>
            <a href="javascript:history.back()">返回上一页</a>
        </strong>
    </div>
</div>

<div class="animate below"></div>
<div class="animate above"></div>
