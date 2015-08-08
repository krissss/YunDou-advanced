<?php

namespace frontend\controllers;

use yii\web\Controller;

class ServiceController extends Controller
{
    /** 我要咨询 */
    public function actionConsult(){
        echo "<h1>我要咨询，建设中。。。</h1>";
    }

    /** 我要报名 */
    public function actionEnroll(){
        echo "<h1>我要报名，建设中。。。</h1>";
    }

    /** 我要建议 */
    public function actionSuggest(){
        echo "<h1>我要建议，建设中。。。</h1>";
    }
}