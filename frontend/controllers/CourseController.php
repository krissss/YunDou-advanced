<?php

namespace frontend\controllers;

use yii\web\Controller;

class BuildingController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
}