<?php
/** 管理员 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\ManagerFilter;
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
            ],[
                'class' => ManagerFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $users = Users::find()->where(['>=','role',Users::ROLE_ADMIN])->all();
        return $this->render('index',[
            'users' => $users
        ]);
    }

}