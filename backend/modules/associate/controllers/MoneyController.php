<?php

namespace backend\modules\associate\controllers;

use common\models\Withdraw;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;

class MoneyController extends Controller
{
    public function actionIndex(){
        $user=Yii::$app->session->get('user');
        $query = Withdraw::find()
            ->where(['userId'=>$user['userId']]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }
}