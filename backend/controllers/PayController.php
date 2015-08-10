<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use Yii;
use common\models\Pay;
use common\models\Users;
use yii\web\Controller;
use yii\data\Pagination;

class PayController extends Controller

{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }
    public function actionIndex()
    {
        $query = Pay::find();
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
   public function actionSearch(){
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
                   $table_a = Pay::tableName();
                   $table_b = Users::tableName();
                   $query = Pay::find()
                       ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                       ->where(['like', "$table_b.username", $content]);
                   break;
               case 'money-more':
                   $query = Pay::find()
                       ->Where(['>','money',$content]);
                   break;
               case 'money-equal':
                   $query = Pay::find()
                       ->where(['=','money',$content]);
                   break;
               case 'money-less':
                   $query = Pay::find()
                       ->Where(['<','money',$content]);
                   break;
               case 'bitcoin-more':
                   $query = Pay::find()
                       ->Where(['>','bitcoin',$content]);
                   break;
               case 'bitcoin-equal':
                   $query = Pay::find()
                       ->where(['=','bitcoin',$content]);
                   break;
               case 'bitcoin-less':
                   $query = Pay::find()
                       ->Where(['<','bitcoin',$content]);
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
                   $table_a = Pay::tableName();
                   $table_b = Users::tableName();
                   $query = Pay::find()
                       ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                       ->where([ "$table_b.role"=>$role]);
                   break;
               default:
                   $query = Pay::find()
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
    public function actionGetMoney(){
        $query = Pay::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('get-money', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

}