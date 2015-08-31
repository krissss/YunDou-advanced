<?php

namespace backend\modules\associate\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    public $layout = "main";

    public function actionIndex()
    {
        return $this->render('index');
    }
}
