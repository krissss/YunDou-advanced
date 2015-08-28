<?php
/** 大客户列表 */
namespace backend\controllers;

use backend\filters\UserLoginFilter;
use backend\models\forms\AddUserForm;
use common\functions\CommonFunctions;
use common\models\Users;
use yii\base\Exception;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\web\Controller;
use Yii;

class UserBigController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    /** 列表 */
    public function actionIndex(){
        Url::remember();
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query->where(['role'=>Users::ROLE_BIG])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    /** 增加或更新方案 */
    public function actionAddUpdate(){
        $request = Yii::$app->request;
        if($request->isPost){
            $userId = $request->post("userId");
            if($userId){
                $addUserForm = AddUserForm::initWithIdOrRole($userId);
            }else{
                $addUserForm = AddUserForm::initWithIdOrRole(null,Users::ROLE_BIG);
            }
            return $this->renderAjax('user-form',[
                'addUserForm' => $addUserForm
            ]);
        }
        CommonFunctions::createAlertMessage("非法获取","error");
        return $this->redirect(['user-big/index']);
    }

    /** 生成用户 */
    public function actionGenerate(){
        $addUserForm = new AddUserForm();
        if($addUserForm->load(Yii::$app->request->post()) && $addUserForm->validate()) {
            $addUserForm->recordOne();
            CommonFunctions::createAlertMessage("大客户设置成功","success");
            return $this->redirect(['user-big/index']);
        }
        CommonFunctions::createAlertMessage("大客户设置失败，参数不全或存在非法字段","error");
        return $this->redirect(['user-big/index']);
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
            return $this->redirect(['user-big/index']);
        }

    }

    /** 查询 */
    public function actionSearch(){
        Url::remember();
        $request = Yii::$app->request;
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        switch ($type) {
            case 'nickname':case 'cellphone':case 'address':case 'recommendCode':
                $query = Users::find()
                    ->where(['like', $type, $content]);
                break;
            default:
                $query = Users::find();
                break;
        }
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query
            ->andWhere(['role'=>Users::ROLE_BIG])
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['registerDate'=>SORT_DESC])
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

}