<?php
/** 推荐分享的页面，所有人都能访问 */
namespace frontend\controllers;

use common\models\Users;
use yii\web\Controller;

class ShareController extends Controller
{
    public function actionIndex($userId){
        $user = Users::findOne($userId);
        return $this->render('index',[
            'user' => $user
        ]);
    }

}