<?php
/** 提现管理 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\DevelopFilter;
use backend\filters\OperationFilter;
use backend\filters\SaleFilter;
use backend\filters\UserLoginFilter;
use backend\models\forms\UpdateWithdrawForm;
use common\models\Withdraw;
use Yii;
use common\models\Users;
use yii\web\Controller;
use yii\data\Pagination;
use common\functions\CommonFunctions;

class WithdrawController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ],[
                'class' => OperationFilter::className(),
                'except'=>['index','search']
            ],[
                'class' => DevelopFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $session = Yii::$app->session;
        $session->remove('query');
        $session->remove('view');
        $query = Withdraw::find();
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

    /**填写发票单号和回复内容 */
    public function actionInit(){
        $request = Yii::$app->request;
        if($request->isPost){
            $withdrawId = $request->post('withdrawId');
            if($withdrawId){
                $updateWithdrawForm = UpdateWithdrawForm::initWithId($withdrawId);
                return $this->renderAjax('withdraw-form',[
                    'updateWithdrawForm'=>$updateWithdrawForm
                ]);
            }
        }else{
            CommonFunctions::createAlertMessage("非正常请求，错误！",'error');
        }
        return $this->redirect(['withdraw/index']);
    }

    /**处理申请提现 */
    public function actionGenerate(){
        $updateWithdrawForm = new UpdateWithdrawForm();
        $request = Yii::$app->request;
        if($updateWithdrawForm->load($request->post()) && $updateWithdrawForm->validate()) {
            $type = $request->post("type");
            if($type == 'refuse'){
                $state = Withdraw::STATE_REFUSE;
            }elseif($type == 'agree'){
                $state = Withdraw::STATE_PASS;
            }else{
                CommonFunctions::createAlertMessage("提交的状态类型未知",'error');
                return $this->redirect(['withdraw/index']);
            }
            $updateWithdrawForm->updateWithdraw($state);
            CommonFunctions::createAlertMessage("处理成功","success");

            return $this->redirect(['withdraw/index']);
        }
        else{
            CommonFunctions::createAlertMessage("处理失败","error");
            return $this->redirect(['withdraw/index']);
        }
    }
    /**提现查询 */
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
                    $table_a = Withdraw::tableName();
                    $table_b = Users::tableName();
                    $query = Withdraw::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(['like', "$table_b.nickname", $content]);
                    break;
                case 'money-more':
                    $query = Withdraw::find()
                        ->Where(['>=', 'money', $content]);
                    break;
                case 'money-equal':
                    $query = Withdraw::find()
                        ->where(['==', 'money', $content]);
                    break;
                case 'money-less':
                    $query = Withdraw::find()
                        ->Where(['<=', 'money', $content]);
                    break;
                case 'apply';
                    $query = Withdraw::find()
                        ->where(['state'=>$content]);
                    break;
                case 'agree';
                    $query = Withdraw::find()
                        ->where(['state'=>$content]);
                    break;
                case 'refuse';
                    $query = Withdraw::find()
                        ->where(['state'=>$content]);
                    break;
                case 'role':
                    $role = '';
                    if ($content == 'a' || $content == 'A' || $content == 'A级') {
                        $role = Users::ROLE_A;
                    }elseif (strstr('金牌伙伴',$content)) {
                        $role = Users::ROLE_AA;
                    } elseif (strstr('钻石伙伴',$content)){
                        $role = Users::ROLE_AAA;
                    } elseif ($content == '管理员') {
                        $role = Users::ROLE_ADMIN;
                    }
                    $table_a = Withdraw::tableName();
                    $table_b = Users::tableName();
                    $query = Withdraw::find()
                        ->leftJoin($table_b, "$table_a.userId=$table_b.userId")
                        ->where(["$table_b.role" => $role]);
                    break;
                default:
                    $query = Withdraw::find()
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
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
        return $this->render('index', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

}