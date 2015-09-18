<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->registerCssFile('/frontend/web/css/404.css',['depends' => \frontend\assets\AppAsset::className()]);
$this->title = 404;
?>
<div id="wrap">
    <div>
        <img src="./images/404/404.png" alt="404" />
    </div>
    <div id="text">
        <strong>
            <span>
                <img src="./images/404/404_info.png" alt="404" style="width: 128px; height: 29px;"/>
            </span>
            <a href="<?=Url::to(['site/index'])?>">返回首页</a>
            <a href="javascript:history.back()">返回上一页</a>
        </strong>
    </div>
</div>

<div class="animate below"></div>
<div class="animate above"></div>
