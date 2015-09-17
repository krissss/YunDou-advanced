<?php
/** 提现方案管理 */
namespace backend\controllers;

use yii\web\Controller;
use backend\filters\AdminFilter;
use backend\filters\DevelopFilter;
use backend\filters\ManagerFilter;
use backend\filters\UserLoginFilter;
use common\models\Scheme;

class WithdrawSchemeController extends Controller
{
    public function behaviors(){
    return [
        'access' => [
            'class' => UserLoginFilter::className(),
        ],[
            'class' => AdminFilter::className(),
        ],[
            'class' => ManagerFilter::className(),
            'except'=>['index','search']
        ],[
            'class' => DevelopFilter::className(),
        ]
    ];
}

    /** 提现设置首页 */
    public function actionIndex(){
    $schemes = Scheme::findAllWithdrawScheme();
    return $this->render('index', [
        'schemes' => $schemes,
    ]);
}
}