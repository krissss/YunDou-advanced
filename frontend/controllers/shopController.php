<?php
/** 云宝商城 */
namespace frontend\controllers;

use yii\web\Controller;

class shopController extends Controller
{
    public function actionIndex(){
        return $this->render('index');
    }
}