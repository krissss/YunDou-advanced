<?php

namespace backend\modules\customer\controllers;

use common\models\Money;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;


class RechargeController extends Controller{

    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $query = Money::find()->where(['userId'=>$user['userId']])->andWhere(['type'=>Money::TYPE_PAY]);
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

    /** 查询 */
    public function actionSearch(){
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
            $user = Yii::$app->session->get('user');
            switch ($type) {
                case 'pay-more':
                    $query = Money::find()
                        ->where(['userId'=>$user['userId'],'type'=>Money::TYPE_PAY])
                        ->andWhere(['>','money',$content]);
                    break;
                case 'pay-equal':
                    $query = Money::find()
                        ->where(['userId'=>$user['userId'],'type'=>Money::TYPE_PAY])
                        ->andWhere(['=','money',$content]);
                    break;
                case 'pay-less':
                    $query = Money::find()
                        ->where(['userId'=>$user['userId'],'type'=>Money::TYPE_PAY])
                        ->andWhere(['<','money',$content]);
                    break;
                default:
                    $query = Money::find()->where(['userId'=>$user['userId'],'type'=>Money::TYPE_PAY]);
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