<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->registerCssFile('/YunDou-advanced/frontend/web/css/404.css',['depends' => \frontend\assets\AppAsset::className()]);
$this->title = 404;
?>
<div id="wrap">
    <div>
        <img src="./images/404/offline.png" alt="网站维护" />
    </div>
    <div id="text">
        <strong>
            <span style="color: #fff; font-size: 12px;">
                让网站休息一会。。。
            </span>
            <a href="<?=Url::to(['site/index'])?>">返回首页</a>
            <a href="javascript:history.back()">返回上一页</a>
        </strong>
    </div>
</div>

<div class="animate below"></div>
<div class="animate above"></div>