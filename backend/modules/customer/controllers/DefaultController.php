<?php

namespace backend\modules\customer\controllers;

use backend\modules\customer\models\forms\UpdateUserForm;
use common\functions\CommonFunctions;
use common\models\Users;
use Yii;
use yii\web\Controller;
use yii\helpers\Url;
use backend\modules\customer\models\forms\ModifyPassWordForm;

class DefaultController extends Controller
{
    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $user = Users::findOne($user['userId']);
        if($user['password'] == CommonFunctions::encrypt("123456")){
            if(!CommonFunctions::isExistAlertMessage()){
                CommonFunctions::createAlertMessage("您的登录密码过于简单，请及时修改","warning");
            }
        }
        if($user['state']==Users::STATE_FROZEN){
            if(!CommonFunctions::isExistAlertMessage()){
                CommonFunctions::createAlertMessage("您的帐号已被冻结，部分操作不可见，需要解冻请联系管理员","warning");
            }
        }
        return $this->render('index',[
            'user' => $user
        ]);
    }

    /** 修改密码 */
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

    /** 修改联系人信息 */
    public function actionUpdateUser(){
        $user = Yii::$app->session->get('user');
        $updateUserForm = UpdateUserForm::initUser($user['userId']);
        if($updateUserForm->load(Yii::$app->request->post())&&$updateUserForm->validate()){
            $updateUserForm->updateUser($user['userId']);
            CommonFunctions::createAlertMessage("修改成功",'success');
            return $this->redirect(['default/index']);
        }
        return $this->render('update-user', [
            'user' => $user,
            'updateUserForm'=>$updateUserForm
        ]);
    }

    /** 修改用户银行卡信息  */
    public function actionUpdateBank(){
        $user = Yii::$app->session->get('user');
        $updateUserForm = UpdateUserForm::initUser($user['userId']);
        if($updateUserForm->load(Yii::$app->request->post())&&$updateUserForm->validate()){
            $updateUserForm->updateBank($user['userId']);
            CommonFunctions::createAlertMessage("修改成功",'success');
            return $this->redirect(['default/index']);
        }
        return $this->render('update-bank', [
            'user' => $user,
            'updateUserForm'=>$updateUserForm
        ]);
    }

}