<?php

namespace frontend\controllers;

use yii\web\Controller;

class CourseController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
}