<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    //网站维护打开以下注释行
    //'catchAll' => ['site/offline'],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    //记录网站所有的错误和异常
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    //记录网站各种必须解决的错误和异常
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'except'=>[
                        'yii\debug\*',  //排除debug
                        'yii\web\HttpException:404',    //排除404
                    ],
                    'logFile' => '@app/runtime/logs/solve/undo.log',
                    'maxFileSize' => 1024,
                    'maxLogFiles' => 20,
                ],
                [
                    //记录微信充值
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['info'],
                    'categories'=>['wx'],
                    'logVars' => [],
                    'logFile' => '@app/runtime/logs/wx/pay.log',
                    'maxFileSize' => 1024,
                    'maxLogFiles' => 100,
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
