<?php
/** AAA级用户列表 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\OperationFilter;
use backend\filters\SaleFilter;
use backend\filters\UserLoginFilter;
use backend\models\forms\AddUserForm;
use common\functions\CommonFunctions;
use common\models\Department;
use common\models\MajorJob;
use common\models\Province;
use common\models\Users;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class UserAaaController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ],[
                'class' => OperationFilter::className(),
                'except'=>['index','search','previous','link-user','search-link']
            ],[
                'class' => SaleFilter::className(),
                'except'=>['index','search','previous']
            ]
        ];
    }

    /** 列表 */
    public function actionIndex(){
        Url::remember();
        $query = Users::find()->where(['role'=>Users::ROLE_AAA]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 增加或更新用户 */
    public function actionAddUpdate(){
        $request = Yii::$app->request;
        if($request->isPost){
            $userId = $request->post("userId");
            if($userId){
                $addUserForm = AddUserForm::initWithIdOrRole($userId);
            }else{
                $addUserForm = AddUserForm::initWithIdOrRole(null,Users::ROLE_AAA);
            }
            $departments = Department::findAllForObject();
            return $this->renderAjax('user-form',[
                'addUserForm' => $addUserForm,
                'departments' => $departments,
            ]);
        }
        CommonFunctions::createAlertMessage("非法获取","error");
        return $this->redirect(['user-aaa/index']);
    }

    /** 生成用户 */
    public function actionGenerate(){
        $addUserForm = new AddUserForm();
        if($addUserForm->load(Yii::$app->request->post()) && $addUserForm->validate()) {
            $addUserForm->recordOne();
            CommonFunctions::createAlertMessage("伙伴设置成功","success");
            return $this->redirect(['user-aaa/index']);
        }
        if(!CommonFunctions::isExistAlertMessage()){
            CommonFunctions::createAlertMessage("伙伴设置失败，参数不全或存在非法字段","error");
        }
        return $this->redirect(['user-aaa/index']);
    }

    /** 修改状态 */
    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $newState = $request->post('newState');
            $userId = intval($request->post('id'));
            Users::updateState($userId,$newState);
            CommonFunctions::createAlertMessage("状态修改成功","success");
        }else{
            throw new Exception('非ajax提交');
        }
    }

    /** 跳转回上一个记住的网页 */
    public function actionPrevious(){
        $previous = Url::previous();
        if($previous){
            return $this->redirect($previous);
        }else{
            return $this->redirect(['user-aaa/index']);
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
            switch ($type) {
                case 'nickname':
                case 'cellphone':
                case 'address':
                case 'recommendCode':
                    $query = Users::find()
                        ->where(['like', $type, $content]);
                    break;
                case 'department':
                    $query = Users::find()
                        ->where(['departmentId'=>$content]);
                    break;
                default:
                    $query = Users::find();
                    break;
            }
            $query = $query->andWhere(['role' => Users::ROLE_AAA]);    //添加用户条件，位置不可改
        }

        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 关联用户 */
    public function actionLinkUser(){
        $request = Yii::$app->request;
        $userId = $request->get('id');
        if(!$userId){
            CommonFunctions::createAlertMessage("缺少对应的用户序号","error");
            return $this->redirect(['user-big/previous']);
        }
        $recommendUser = Users::findOne($userId);
        Yii::$app->session->setFlash('recommendUser',$recommendUser);   //记录推荐用户，便于查询页面使用
        $query = Users::find()->where(['recommendUserID'=>$recommendUser['userId']]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('link-user',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 关联用户查询 */
    public function actionSearchLink(){
        Url::remember();
        $session = Yii::$app->session;
        $request = Yii::$app->request;
        $recommendUser = $session->getFlash('recommendUser');
        $query = $session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query) {
            switch ($type) {
                case 'nickname':
                case 'cellphone':
                    $query = Users::find()
                        ->where(['like', $type, $content]);
                    break;
                case 'province':
                    $table_a = Users::tableName();
                    $table_b = Province::tableName();
                    $query = Users::find()
                        ->leftJoin($table_b, "$table_a.provinceId=$table_b.provinceId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'majorJob':
                    $table_a = Users::tableName();
                    $table_b = MajorJob::tableName();
                    $query = Users::find()
                        ->leftJoin($table_b, "$table_a.majorJobId=$table_b.majorJobId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                default:
                    $query = Users::find();
                    break;
            }
            $query = $query->andWhere(['recommendUserID' => $recommendUser['userId']]);    //添加用户条件，位置不可改
        }
        //继续存储，点击下一页或者其他查询后继续使用
        $session->setFlash('recommendUser',$recommendUser);
        $session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('link-user',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

}