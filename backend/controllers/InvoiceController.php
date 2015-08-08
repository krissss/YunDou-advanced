<?php

namespace backend\controllers;

use Yii;
use common\models\invoice;
use common\models\Users;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class InvoiceController extends Controller

{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $query = invoice::find();
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

    public function actionSearch()
{
    $request = Yii::$app->request;
    $query = Yii::$app->session->getFlash('query');
    if($request->isPost){
        $type = $request->post('type');
        $content = $request->post('content');
    }else{
        $type = $request->get('type');
        $content = trim($request->get('content'));
    }
    if($type || !$query){
        switch ($type) {
            case 'username';
                $table_a = invoice::tableName();
                $table_b = Users::tableName();
                $query = invoice::find()
                    ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                    ->where(['like', "$table_b.username", $content]);
                break;
            case 'money-more':
                $query = invoice::find()
                    ->Where(['>','money',$content]);
                break;
            case 'money-equal':
                $query = invoice::find()
                    ->where(['=','money',$content]);
                break;
            case 'money-less':
                $query = invoice::find()
                    ->Where(['<','money',$content]);
                break;
            case 'role':
                $role='';
                if( $content=='a'||$content == 'A'|| $content=='A级'){
                    $role = Users::ROLE_A;
                }  elseif($content =='aa'||$content =='AA'||$content=='AA级')
                {
                    $role =Users::ROLE_AA;
                }
                elseif($content =='aaa'||$content =='AAA'||$content=='AAA级')
                {
                    $role =Users::ROLE_AAA;
                }
                elseif($content =='管理员')
                {
                    $role =Users::ROLE_ADMIN;
                }
                $table_a = invoice::tableName();
                $table_b = Users::tableName();
                $query = invoice::find()
                    ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                    ->where([ "$table_b.role"=>$role]);
                break;
            default:
                $query = invoice::find()
                    ->where(['like', $type, $content]);
                break;
        }
    }
    Yii::$app->session->setFlash('query',$query);
    $pagination = new Pagination([
        'defaultPageSize' => Yii::$app->params['pageSize'],
        'totalCount' => $query->count(),
    ]);
    $model = $query->offset($pagination->offset)
        ->limit($pagination->limit)
        ->all();
    return $this->render('index',[
        'models' => $model,
        'pages' => $pagination
    ]);
}
    public function actionOpener()
    {
        $query = invoice::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('opener', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

}