<?php
namespace backend\controllers;

use backend\filters\UserLoginFilter;
use backend\models\forms\LoginForm;
use common\models\Users;
use Yii;
use yii\base\Exception;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
                'except' => ['login','captcha','error','offline']
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'height' => 38,
                'minLength' => 4,
                'maxLength' => 5
            ],
        ];
    }

    public function actionIndex(){
        $user = Yii::$app->session->get('user');
        if($user['role'] >= Users::ROLE_ADMIN){
            return $this->redirect(['user-a/index']);
        }elseif($user['role'] == Users::ROLE_BIG){
            return $this->redirect(['customer/default/index']);
        }elseif($user['role']==Users::ROLE_AA || $user['role']==Users::ROLE_AAA){
            return $this->redirect(['associate/default/index']);
        }elseif($user['role'] == Users::ROLE_A){
            return $this->redirect(['site/login']);
        }else{
            return $this->render('error');
        }
    }

    public function actionLogin(){
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()){
            return $this->redirect(['site/index']);
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLogout(){
        Yii::$app->getSession()->removeAll();
        return $this->redirect(['site/login']);
    }

    public function actionOffline(){
        return $this->render('offline');
    }

}
