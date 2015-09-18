<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->registerCssFile('/frontend/web/css/404.css',['depends' => \frontend\assets\AppAsset::className()]);
$this->title = "没有错题集";
?>
<div id="wrap">
    <div>
        <img src="./images/404/no_wrong.png" alt="<?=$this->title?>" />
    </div>
    <div id="text">
        <strong>
            <span style="color: #fff; font-size: 12px;">
                您在练习中做错的题将自动保存到此
            </span>
            <a href="<?=Url::to(['site/index'])?>">返回首页</a>
            <a href="javascript:history.back()">返回上一页</a>
        </strong>
    </div>
</div>

<div class="animate below"></div>
<div class="animate above"></div>
