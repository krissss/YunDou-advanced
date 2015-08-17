<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\models\Pay;
use Yii;
use common\models\Invoice;
use common\models\Users;
use yii\base\Exception;
use yii\web\Controller;
use yii\data\Pagination;
use common\functions\CommonFunctions;
use yii\web\NotFoundHttpException;

class InvoiceController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex(){
        $query = Invoice::find()->orderBy(['createDate'=>SORT_DESC]);
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

    public function actionApply(){
        $query = Invoice::find()->where(['state'=>Invoice::STATE_ING])->orderBy(['createDate'=>SORT_DESC]);;
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('apply', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionOpener(){
        $request = Yii::$app->request;
        if($request->isPost) {
            $invoiceId = $request->post('invoiceId');
            $orderNumber=$request->post('orderNumber');
            Invoice::updateOrderNumber($invoiceId,$orderNumber);
            CommonFunctions::createAlertMessage("填写快递单号成功","success");
        }
        $query = Invoice::find()->where(['state'=>Invoice::STATE_PASS])->orderBy(['createDate'=>SORT_DESC]);;
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
            switch ($type) {
                case 'nickname';
                    $table_a = Invoice::tableName();
                    $table_b = Users::tableName();
                    $query = Invoice::find()
                        ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                        ->where(['like', "$table_b.username", $content]);
                    break;
                case 'money-more':
                    $query = Invoice::find()
                        ->Where(['>', 'money', $content]);
                    break;
                case 'money-equal':
                    $query = Invoice::find()
                        ->where(['=', 'money', $content]);
                    break;
                case 'money-less':
                    $query = Invoice::find()
                        ->Where(['<', 'money', $content]);
                    break;
                case 'role':
                    $role = '';
                    if ($content == 'a' || $content == 'A' || $content == 'A级') {
                        $role = Users::ROLE_A;
                    } elseif ($content == 'aa' || $content == 'AA' || $content == 'AA级') {
                        $role = Users::ROLE_AA;
                    } elseif ($content == 'aaa' || $content == 'AAA' || $content == 'AAA级') {
                        $role = Users::ROLE_AAA;
                    } elseif ($content == '管理员') {
                        $role = Users::ROLE_ADMIN;
                    }
                    $table_a = Invoice::tableName();
                    $table_b = Users::tableName();
                    $query = Invoice::find()
                        ->leftJoin($table_b, "$table_a.UserId=$table_b.UserId")
                        ->where(["$table_b.role" => $role]);
                    break;
                default:
                    $query = Invoice::find()
                        ->where(['like', $type, $content]);
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
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionFind(){
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
                case 'money-more':
                    $query = Invoice::find()
                        ->Where(['>','money',$content]);
                    break;
                default:
                    $query = Invoice::find()
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
        return $this->render('opener',[
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isPost){
            $invoiceId = $request->post('invoiceId');
            $state = $request->post('state');
            $replyContent = $request->post('replyContent');
            if($state == 'agree'){
                $invoiceState = Invoice::STATE_PASS;
                CommonFunctions::createAlertMessage("已同意开票",'success');
            }elseif($state == 'refuse'){
                $invoiceState = Invoice::STATE_REFUSE;
                CommonFunctions::createAlertMessage("已拒绝开票",'success');
            }else{
                throw new Exception("state undefined");
            }
            Invoice::changeState($invoiceId,$invoiceState,$replyContent);
        }else{
            CommonFunctions::createAlertMessage("非正常请求，错误！",'error');
        }
        return $this->redirect(['invoice/apply']);
    }

}