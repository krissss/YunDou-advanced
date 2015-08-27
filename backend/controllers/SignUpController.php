<?php
/** 报名管理 */
namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\functions\CommonFunctions;
use common\models\Info;
use common\models\Users;
use Yii;
use yii\base\Exception;
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
                case 'IDCard': case 'cellphone': case 'realName':
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

    /** 查看 */
    public function actionView(){
        $request = Yii::$app->request;
        if($request->isPost){
            $infoId = $request->post('infoId');
            return $this->renderAjax('info',[
                'info' => Info::findOne($infoId)
            ]);
        }else{
            throw new Exception("非正常请求");
        }
    }

    /** 下载图片 */
    public function actionDownload(){
        $request = Yii::$app->request;
        $file = $request->get('file');
        if(!is_dir('../../frontend/web/'.$file) && file_exists('../../frontend/web/'.$file)){
            return Yii::$app->response->sendFile('../../frontend/web/'.$file);
        }else{
            return "<h1>文件不存在</h1>";
        }
    }

    /** 修改状态 */
    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isPost){
            $infoId = $request->post('infoId');
            $state = $request->post('state');
            if($state == 'ok'){
                if($result = Info::changeState($infoId,Info::STATE_PASS)){
                    return $result;
                }
            }elseif($state == 'error'){
                $replyContent = $request->post('replyContent');
                if($result = Info::changeState($infoId,Info::STATE_REFUSE,$replyContent)){
                    return $result;
                }
            }else{
                return "状态未定义";
            }
            return 'ok';
        }else{
            throw new Exception("非正常请求");
        }
    }
}