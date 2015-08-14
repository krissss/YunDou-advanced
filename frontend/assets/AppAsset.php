<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        'css/loading.css',
        'css/yundou2.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        'frontend\assets\BootstrapAsset',
    ];
}
