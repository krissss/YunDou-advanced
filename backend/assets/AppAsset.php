<?php

namespace backend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/site.css',
        //bootstrap
        'css/bootstrap.min.css',
        //字体图标
        'css/font-awesome.min.css',
        'css/typicons.min.css',
        //beyond
        'css/beyond.min.css',
        'css/animate.min.css',
        'css/azure.min.css',
        //yundou
        'css/yundou.css',
    ];
    public $js = [
        //bootstrap
        'js/bootstrap.min.js',
        //beyond
        'js/beyond.min.js',
        //yundou
        'js/yundou.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}
