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
        'css/yundou.css?2', //添加随机数确保每次修改后微信端用户能够跟随修改
    ];
    public $js = [
        //'js/yundou.min.js',
        'js/yundou.js?2',   //添加随机数确保每次修改后微信端用户能够跟随修改
    ];
    public $depends = [
        'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
        'frontend\assets\BootstrapAsset',
    ];
}
