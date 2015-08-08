<?php

namespace backend\controllers;

use backend\functions\CommonFunctions;
use common\models\Users;
use frontend\functions\DateFunctions;
use Yii;
use common\models\Service;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ServiceController extends Controller

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
        $query = Service::find()->orderBy(['createDate'=>SORT_DESC]);
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

    public function actionDelete($id){
        /** @var  $service \common\models\Service */
        $service = Service::findOne($id);
        $service->delete();
        return $this->redirect(['exam-template/index']);
    }

    public function actionSearch()
    {
        $request = Yii::$app->request;
        $type = $request->get("type");
        $query = Yii::$app->session->getFlash('query');
        if(!$type){
            $type = $request->post("type");
            $content=$request->post("content");
        }else{
            $content=$request->get("content");
        }
        if($type || !$query) {
            switch ($type) {
                case 'date':
                    $query = Service::find()
                        ->where(['between','createDate',date('Y-m-d H:i:s',strtotime($content)),date('Y-m-d H:i:s')]);
                    break;
                case 'state':
                    if($content=='noReply'){
                        $query = Service::find()->where(['reply'=>null]);
                    }elseif($content=='replied'){
                        $query = Service::find()->where('reply is not null');
                    }else{
                        $query = Service::find();
                    }
                    break;
                case 'nickname':
                    $table_a = Service::tableName();
                    $table_b = Users::tableName();
                    $query = Service::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'content':
                    $query = Service::find()->where(['like', $type, $content]);
                    break;
                default:
                    $query = Service::find();
                    break;
            }
        }
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' =>$query->count()
        ]);
        $model = $query->orderBy(['createDate'=>SORT_DESC])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionReply(){
        $request = Yii::$app->request;
        if($request->isPost){
            $serviceId = $request->post('serviceId');
            $reply = $request->post('reply');
            Service::replyService($serviceId,$reply);
        }else{
            CommonFunctions::createAlertMessage("非正常请求，错误！",'error');
        }
        return $this->redirect(['service/index']);
    }

}