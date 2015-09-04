<?php

namespace backend\modules\associate\controllers;

use common\models\Scheme;
use common\models\UsageMode;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\IncomeConsume;

class MoneyController extends Controller
{
    public function actionIndex(){
        $user=Yii::$app->session->get('user');
        $query = IncomeConsume::find()
            ->where(['userId'=>$user['userId']])
            ->andwhere(['UsageModeId'=>UsageMode::USAGE_WITHDRAW]);
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