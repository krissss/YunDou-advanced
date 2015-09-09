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
            ->orderBy(['withdrawId'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    /** 提现金额查询 */
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
                case 'withdraw-more':
                    $query = Withdraw::find()
                        ->where(['userId'=>$user['userId']])
                        ->andWhere(['>=', 'money', $content]);
                    break;
                case 'withdraw-equal':
                    $query = Withdraw::find()
                        ->where(['userId'=>$user['userId']])
                        ->andWhere(['=', 'money', $content]);
                    break;

                case 'withdraw-less':
                    $query = Withdraw::find()
                        ->where(['userId'=>$user['userId']])
                        ->andWhere(['<=', 'money', $content]);
                    break;
                case 'state':
                    $query = Withdraw::find()
                        ->where(['userId'=>$user['userId']])
                        ->andWhere(['state'=>$content]);
                    break;
                default:
                    $query = Withdraw::find();
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