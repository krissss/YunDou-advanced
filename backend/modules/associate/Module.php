<?php

namespace backend\modules\associate;

use backend\filters\AssociateFilter;
use backend\filters\UserLoginFilter;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\associate\controllers';
    public $layout = "main";

    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AssociateFilter::className(),
            ]
        ];
    }

    public function init()
    {
        parent::init();
    }
}
