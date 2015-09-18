<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    //网站维护打开以下注释行
    //'catchAll' => ['site/offline'],
    'modules' => [
        'associate' => [
            'class' => 'backend\modules\associate\Module',
        ],
        'customer' => [
            'class' => 'backend\modules\customer\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => 'yii\log\EmailTarget',
                    'levels' => ['warning','error'],
                    'except'=>[
                        'yii\debug\Module',
                        'yii\web\HttpException:404',
                    ],
                    'message' => [
                        'from' => ['sjh@yunbaonet.cn'],
                        'to' => ['sjh@yunbaonet.cn'],
                        'subject' => '云豆后台Exception',
                    ],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
