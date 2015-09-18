<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->redirect(['practice/index']);
    }

    public function actionTest(){
        return $this->render('test');
    }

    public function actionOffline(){
        return $this->render('offline');
    }

    /** 题库建设中 */
    public function actionTestLibraryNotFound(){
        return $this->render('test-library-not-found');
    }
    /** 错题数为0 */
    public function actionTestLibraryWrongZero(){
        return $this->render('test-library-wrong-zero');
    }
    /** 重点题为0 */
    public function actionTestLibraryCollectionZero(){
        return $this->render('test-library-collection-zero');
    }
}
