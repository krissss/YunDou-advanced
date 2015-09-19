<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=yundou',
            'username' => 'root',
            'password' => 'root',
            'charset' => 'utf8',
            //添加数据库表缓存
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 24*3600,
            'schemaCache' => 'cache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.exmail.qq.com',
                'username' => 'sjh@yunbaonet.cn',
                'password' => 'asd7798',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['sjh@yunbaonet.cn'=>'www.yunbaonet.cn']
            ],
            'useFileTransport' => false,//设为false开启真实发送邮件，true只是保存在runtime中
        ],
    ],
];
