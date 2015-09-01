<?php
/** 咨询建议 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\UserLoginFilter;
use common\functions\CommonFunctions;
use common\models\Users;
use Yii;
use common\models\Service;
use yii\web\Controller;
use yii\data\Pagination;

class ServiceController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
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
                case 'userId':
                    $query = Service::find()
                        ->where(['userId'=>$content]);
                    break;
                case 'date':
                    $query = Service::find()
                        ->where(['between','createDate',date('Y-m-d H:i:s',strtotime($content)),date('Y-m-d H:i:s')]);
                    break;
                case 'state':
                    if($content=='noReply'){
                        $query = Service::find()->where(['state'=>Service::STATE_UNREPLY]);
                    }elseif($content=='replied'){
                        $query = Service::find()->where(['state'=>Service::STATE_REPLIED]);
                    }elseif($content=='publish'){
                        $query = Service::find()->where(['state'=>Service::STATE_PUBLISH]);
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
        $models = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'models' => $models,
            'pages' => $pagination
        ]);
    }

    public function actionReply(){
        $request = Yii::$app->request;
        if($request->isPost){
            $serviceId = $request->post('serviceId');
            $reply = CommonFunctions::text2Html($request->post('reply'));
            $publish = $request->post('publish');
            if($publish == 'publish'){
                Service::replyService($serviceId,$reply,true);
            }else{
                Service::replyService($serviceId,$reply);
            }
        }else{
            CommonFunctions::createAlertMessage("非正常请求，错误！",'error');
        }
        return $this->redirect(['service/index']);
    }

    public function actionPublish(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $serviceId = $request->post('serviceId');
            return Service::changePublish($serviceId);
        } else {
            CommonFunctions::createAlertMessage("非正常请求，错误！", 'error');
        }
        return $this->redirect(['service/index']);
    }
}