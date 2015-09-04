<?php

namespace backend\modules\associate\controllers;

use Yii;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Users;

class UserController extends Controller
{
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $user = $session->get('user');

        $query = Users::find()->where(['recommendUserID'=>$user['userId']]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $users = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 查询 */
    public function actionSearch(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query) {
            $user = Yii::$app->session->get('user');
            $query = Users::find()->where(['recommendUserID'=>$user['userId']]);
            switch ($type) {
                case 'userId':
                    $query = $query->andWhere(['userId'=>$content]);
                    break;
                case 'nickname':
                case 'cellphone':
                case 'realname':
                    $query = $query->andWhere(['like', $type, $content]);
                    break;
                default:
                    break;
            }
        }
        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }
}