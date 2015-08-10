<?php
/* @var $this \yii\web\View */

use yii\helpers\Url;

$this->title = "404";
$this->params['breadcrumbs'] = [
    'id'=>'empty-container'
];
?>
<body class="body-404">
<div class="error-header"> </div>
<div class="container ">
    <section class="error-container text-center">
        <h1>404</h1>
        <div class="error-divider">
            <h2>页面未找到</h2>
            <p class="description">您访问的页面不存在</p>
        </div>
        <a href="<?=Url::to(['site/index'])?>" class="return-btn"><i class="fa fa-home"></i>首页</a>
    </section>
</div>
</body>
