<?php

namespace backend\modules\customer;

use backend\filters\CustomerFilter;
use backend\filters\UserLoginFilter;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\customer\controllers';
    public $layout = "main";

    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => CustomerFilter::className(),
            ]
        ];
    }

    public function init()
    {
        parent::init();
    }
}
