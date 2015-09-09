<?php

namespace backend\modules\associate\controllers;

use Yii;
use yii\web\Controller;
use common\models\IncomeConsume;
use yii\data\Pagination;

class BitcoinController extends Controller
{
    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $query = IncomeConsume::find()->where(['userId'=>$user['userId']]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    /** 云豆收支查询 */
    public function actionSearch(){
        $user = Yii::$app->session->get('user');
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if ($request->isPost) {
            $type = $request->post('type');
            $content = $request->post('content');
        } else {
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if ($type || !$query) {
            switch ($type) {
                case 'income-more':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME,'userId'=>$user['userId']])
                        ->andWhere(['>=', 'bitcoin', $content]);
                    break;
                case 'income-equal':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME,'userId'=>$user['userId']])
                        ->andwhere(['==', 'bitcoin', $content]);
                    break;
                case 'income-less':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_INCOME,'userId'=>$user['userId']])
                        ->andWhere(['<=', 'bitcoin', $content]);
                    break;
                case 'consume-more':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME,'userId'=>$user['userId']])
                        ->andWhere(['>=', 'bitcoin', $content]);
                    break;
                case 'consume-equal':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME,'userId'=>$user['userId']])
                        ->andwhere(['==', 'bitcoin', $content]);
                    break;
                case 'consume-less':
                    $query = IncomeConsume::find()
                        ->where(['type' => IncomeConsume::TYPE_CONSUME,'userId'=>$user['userId']])
                        ->andWhere(['<=', 'bitcoin', $content]);
                    break;
                default:
                    $query = IncomeConsume::find();
                    break;
            }
        }
        Yii::$app->session->setFlash('query', $query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }
}