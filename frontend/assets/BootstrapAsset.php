<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class BootstrapAsset extends AssetBundle
{
    public $sourcePath = '@bower/bootstrap/dist';
    public $css = [
        'css/bootstrap.css',
    ];
    public $js = [
        'js/bootstrap.js',
    ];
}
