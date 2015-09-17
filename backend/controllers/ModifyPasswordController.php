<?php
/** 修改密码 */
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\UserLoginFilter;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use backend\models\forms\ModifyPasswordForm;

class ModifyPasswordController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ]
        ];
    }

    public function actionIndex(){
        $formModifyPassword = new ModifyPasswordForm();
        if($formModifyPassword->load(Yii::$app->request->post()) && $formModifyPassword->validate()){
            $formModifyPassword->modifyPassword();
            return $this->redirect(Url::to(['/site/logout']));
        }
        return $this->render('index', [
            'formModifyPassword'=>$formModifyPassword
        ]);
    }
}