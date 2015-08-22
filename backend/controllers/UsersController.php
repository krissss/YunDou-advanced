<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\functions\CommonFunctions;
use Yii;
use common\models\Users;
use common\models\Province;
use common\models\MajorJob;
use backend\models\forms\UploadForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
//use server\Spreadsheet_Excel_Reader;
use yii\web\UploadedFile;

class UsersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count()
        ]);
        $users = $query->where('weixin is not null')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    public function actionOther(){
        $query = Users::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $users = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('other',[
            'users' => $users,
            'pages' => $pagination
        ]);
    }

    public function actionSearchWx(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query){
            switch ($type) {
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
                        ->leftJoin($table_b, "$table_a.majorJobId='.$table_b.majorJobId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                case 'role':
                    if ($content == 'A' || $content == 'A级') {
                        $role = Users::ROLE_A;
                    } elseif ($content == 'AA' || $content == 'AA级') {
                        $role = Users::ROLE_AA;
                    } elseif ($content == 'AAA' || $content == 'AAA级') {
                        $role = Users::ROLE_AAA;
                    } elseif ($content == '管理员') {
                        $role = Users::ROLE_ADMIN;
                    } else {
                        CommonFunctions::createAlertMessage("该等级用户不存在", 'warning');
                        return $this->redirect(['users/index']);
                    }
                    $query = Users::find()
                        ->where(['role'=>$role]);
                    break;
                default:
                    $query = Users::find();
                    break;
            }
            $query = $query->andWhere('weixin is not null');    //添加微信用户条件，位置不可改
        }

        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $user = $query
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'users' => $user,
            'pages' => $pagination
        ]);
    }
}
