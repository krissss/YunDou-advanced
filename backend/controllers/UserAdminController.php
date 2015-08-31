<?php
/** 管理员 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\UserLoginFilter;
use common\models\Users;
use yii\web\Controller;

class UserAdminController extends Controller
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
        $users = Users::findAll(['role'=>Users::ROLE_ADMIN]);
        return $this->render('index',[
            'users' => $users
        ]);
    }

}