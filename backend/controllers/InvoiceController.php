<?php
/** 发票管理 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\OperationFilter;
use backend\filters\SaleFilter;
use backend\filters\UserLoginFilter;
use backend\models\forms\UpdateInvoiceForm;
use Yii;
use common\models\Invoice;
use common\models\Users;
use yii\web\Controller;
use yii\data\Pagination;
use common\functions\CommonFunctions;

class InvoiceController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ],[
                'class' => OperationFilter::className(),
                'except'=>['index','search','apply','opener']
            ],[
                'class' => SaleFilter::className(),
            ]
        ];
    }

    /** 发票列表 */
    public function actionIndex(){
        $session = Yii::$app->session;
        $session->remove('query');
        $session->remove('view');
        $query = Invoice::find();
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

    /** 发票申请 */
    public function actionApply(){
        $session = Yii::$app->session;
        $session->remove('query');
        $session->remove('view');
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

    /** 发票开具 */
    public function actionOpener(){
        $request = Yii::$app->request;
        if($request->isPost) {
            $invoiceId = $request->post('invoiceId');
            $orderNumber=$request->post('orderNumber');
            Invoice::updateOrderNumber($invoiceId,$orderNumber);
            CommonFunctions::createAlertMessage("填写快递单号成功","success");
        }
        $session = Yii::$app->session;
        $session->remove('query');
        $session->remove('view');
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

    /** 发票查询 */
    public function actionSearch(){
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
                case 'nickname';
                    $table_a = Invoice::tableName();
                    $table_b = Users::tableName();
                    $query = Invoice::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'money-more':
                    $query = Invoice::find()
                        ->Where(['>=', 'money', $content]);
                    break;
                case 'money-equal':
                    $query = Invoice::find()
                        ->where(['==', 'money', $content]);
                    break;
                case 'money-less':
                    $query = Invoice::find()
                        ->Where(['<=', 'money', $content]);
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
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(["$table_b.role" => $role]);
                    break;
                default:
                    $query = Invoice::find()
                        ->where(['like', $type, $content]);
                    break;
            }
        }

        $view = $session->getFlash('view');
        if(!$view){ //非下一页
            $view = $request->get('view');  //取哪个页面的搜索
            if(!$view){ //不存在即为index的搜索
                $view = 'index';
            }
        }
        if($view == 'apply'){   //发票申请的搜索
            $query->andWhere(['state'=>Invoice::STATE_ING]);
        }elseif($view == 'opener'){ //发票开具的搜索
            $query->andWhere(['state'=>Invoice::STATE_PASS]);
        }
        $session->setFlash('view',$view);
        $session->setFlash('query', $query);

        $pagination = new Pagination([
            'defaultPageSize' =>Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render($view, [
            'models' => $models,
            'pages' => $pagination
        ]);
    }

    /** 填写处理内容 */
    public function actionInit(){
        $request = Yii::$app->request;
        if($request->isPost){
            $invoiceId = $request->post('invoiceId');
            if($invoiceId){
                $updateInvoiceForm = UpdateInvoiceForm::initInvoice($invoiceId);
                return $this->renderAjax('apply-form',[
                    'updateInvoiceForm' => $updateInvoiceForm
                ]);
            }else{
                CommonFunctions::createAlertMessage("非正常请求，错误！",'error');
            }
        }
        return $this->redirect(['invoice/apply']);
    }

    /** 处理申请改变状态 */
    public function actionGenerate(){
        $updateInvoiceForm = new UpdateInvoiceForm();
        $request = Yii::$app->request;
        if($updateInvoiceForm->load($request->post())&&$updateInvoiceForm->validate()){
            $type = $request->post('type');
            if($type=='refuse'){
                $state = Invoice::STATE_REFUSE;
            }elseif($type == 'agree'){
                $state = Invoice::STATE_PASS;
            }else{
                CommonFunctions::createAlertMessage("提交的状态类型未知",'error');
                return $this->redirect(['invoice/apply']);
            }
            $updateInvoiceForm->updateInvoice($state);
            CommonFunctions::createAlertMessage("处理成功",'success');
            return $this->redirect(['invoice/apply']);
        }else{
            CommonFunctions::createAlertMessage("处理失败",'error');
            return $this->redirect(['invoice/apply']);
        }
    }

}