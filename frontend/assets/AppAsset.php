<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    //public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
    public $css = [
        //'css/site.css',
        //mui
        //'css/mui.css',
        //'css/mui.listpicker.css',
        //'css/mui.poppicker.css',
        //yundou
        //'css/yundou.css',
        'css/yundou2.css',
    ];
    public $js = [
        //mui
        //'js/mui.min.js',
        //'js/mui.listpicker.js',
        //'js/mui.poppicker.js',
        //jquery
        //'js/jquery.min.js',
        //yundou
        //'js/yundou.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        //'yii\web\JqueryAsset',
        //'frontend\assets\BootstrapAsset',
    ];
}
