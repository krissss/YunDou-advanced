<?php

namespace frontend\controllers;

use yii\web\Controller;

class AccountController extends Controller
{
    public $layout = 'practice';

    /**
     * 实名认证
     */
    public function actionRegister(){
        return $this->render('register');
    }
}