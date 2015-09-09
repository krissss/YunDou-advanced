<?php

namespace backend\modules\associate\controllers;

use common\functions\CommonFunctions;
use common\models\Users;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use backend\modules\associate\models\forms\ModifyPassWordForm;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        $session = Yii::$app->session;
        $user = $session->get('user');
        $user = Users::findOne($user['userId']);
        if($user['state'] == Users::STATE_FROZEN){
            CommonFunctions::createAlertMessage("您的帐号已被冻结，部分操作不可见，需要解冻请联系管理员","warning");
        }
        return $this->render('index',[
            'user' => $user
        ]);
    }

    public function actionModifyPassword(){
        $formModifyPassword = new ModifyPasswordForm();
        if($formModifyPassword->load(Yii::$app->request->post()) && $formModifyPassword->validate()){
            $formModifyPassword->modifyPassword();
            return $this->redirect(Url::to(['/site/logout']));
        }
        return $this->render('modify-password', [
            'formModifyPassword'=>$formModifyPassword
        ]);
    }
}