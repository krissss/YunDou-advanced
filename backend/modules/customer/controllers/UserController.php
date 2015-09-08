<?php

namespace backend\modules\customer\controllers;

use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\Pagination;
use common\models\Users;
use common\functions\CommonFunctions;

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
            ->orderBy(['state'=>SORT_ASC,'registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 分配云豆 */
    public function actionDistribute(){
        $request=Yii::$app->request;
        if($request->isPost) {
            $user = Yii::$app->session->get('user');
            $bitcoin = intval($request->post('distribute_bitcoin_number'));
            $userId = $request->post('distribute_bitcoin_userId');
            if($bitcoin<=0) {
                CommonFunctions::createAlertMessage("分配云豆失败，因为您未填写云豆数量或云豆数量未大于0","error");
            }elseif($bitcoin>Users::findBitcoin($user['userId'])){
                CommonFunctions::createAlertMessage("分配云豆失败，因为您的云豆余额不足","error");
            }else{
                Users::distributeBitcoin($user['userId'],$userId,$bitcoin);
                CommonFunctions::createAlertMessage("成功分配给用户号为".$userId."的用户".$bitcoin."云豆。","success");
            }
        }else{
            CommonFunctions::createAlertMessage("非法提交","error");
        }
        return $this->redirect(['user/previous']);
    }

    /** 修改员工状态 */
    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $newState = $request->post('newState');
            $userId = intval($request->post('id'));
            if($newState == 'open'){
                $newState = Users::STATE_NORMAL;
                Users::updateState($userId,$newState);
                return 'open';
            }elseif($newState == 'close'){
                $newState = Users::STATE_REMOVE;
                Users::updateState($userId,$newState);
                return 'close';
            }else{
                return '状态非法';
            }
        }else{
            throw new Exception('非ajax提交');
        }
    }

    /** 查询 */
    public function actionSearch(){
        Url::remember();
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
                case 'bitcoin-less':
                    $query = $query->andWhere(['<=','bitcoin',$content]);
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
            ->orderBy(['state'=>SORT_ASC,'registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 跳转回上一个记住的网页 */
    public function actionPrevious(){
        $previous = Url::previous();
        if($previous){
            return $this->redirect($previous);
        }else{
            return $this->redirect(['user/index']);
        }
    }


}