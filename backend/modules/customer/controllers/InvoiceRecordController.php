<?php
/** 发票管理 */
namespace backend\modules\customer\controllers;

use Yii;
use common\models\Invoice;
use yii\web\Controller;
use yii\data\Pagination;
class InvoiceRecordController extends Controller
{

    /** 发票列表 */
    public function actionIndex(){
        $user = Yii::$app->session->get('user');
        $query = Invoice::find()->where(['userId'=>$user['userId']]);
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

    /** 发票查询 */
    public function actionSearch(){
        $user = Yii::$app->session->get('user');
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $query = $session->getFlash('query');
        if ($request->isPost) {
            $type = $request->post('type');
            $content = $request->post('content');
        } else {
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if ($type || !$query) {
            switch ($type) {
                case 'orderNumber':
                    $query = Invoice::find()
                            ->where(['userId'=> $user['userId']])
                            ->andWhere(['like','orderNumber',$content]);
                    break;
                case 'state':
                     $query = Invoice::find()
                         ->where(['userId' => $user['userId'],'state' => $content]);
                    break;
                case 'money-more':
                    $query = Invoice::find()
                        ->where(['userId' => $user['userId']])
                        ->andWhere(['>=', 'money', $content]);
                    break;
                case 'money-equal':
                    $query = Invoice::find()
                        ->where(['userId' => $user['userId']])
                        ->andWhere(['==', 'money', $content]);
                    break;
                case 'money-less':
                    $query = Invoice::find()
                        ->where(['userId' => $user['userId']])
                        ->andWhere(['<=', 'money', $content]);
                    break;
                default:
                    $query = Invoice::find()
                        ->where(['like', $type, $content]);
                    break;
            }
        }
        $pagination = new Pagination([
            'defaultPageSize' =>Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pagination
        ]);
    }
}