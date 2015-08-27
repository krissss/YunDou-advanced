<?php
/** 报名管理 */
namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\functions\CommonFunctions;
use common\models\Info;
use common\models\Users;
use Yii;
use yii\web\Controller;
use yii\data\Pagination;

class SignUpController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex(){
        //echo "待做";exit;
        $query = Info::find()->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pagination
        ]);
    }

    public function actionSearch(){
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
                    $query = Info::find()
                        ->where(['userId'=>$content]);
                    break;
                case 'date':
                    $query = Info::find()
                        ->where(['between','createDate',date('Y-m-d H:i:s',strtotime($content)),date('Y-m-d H:i:s')]);
                    break;
                case 'state':
                    if($content=='record'){
                        $query = Info::find()->where(['state'=>Info::STATE_RECORD]);
                    }elseif($content=='pass'){
                        $query = Info::find()->where(['state'=>Info::STATE_PASS]);
                    }elseif($content=='refuse'){
                        $query = Info::find()->where(['state'=>Info::STATE_REFUSE]);
                    }else{
                        $query = Info::find();
                    }
                    break;
                case 'nickname':
                    $table_a = Info::tableName();
                    $table_b = Users::tableName();
                    $query = Info::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'IDCard':
                    $query = Info::find()->where(['like', $type, $content]);
                    break;
                default:
                    $query = Info::find();
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
            $reply = $request->post('reply');
            $publish = $request->post('publish');
            if($publish == 'publish'){
                Info::replyService($serviceId,$reply,true);
            }else{
                Info::replyService($serviceId,$reply);
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
            return Info::changePublish($serviceId);
        } else {
            CommonFunctions::createAlertMessage("非正常请求，错误！", 'error');
        }
        return $this->redirect(['service/index']);
    }
}