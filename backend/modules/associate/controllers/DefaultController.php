<?php

namespace backend\modules\associate\controllers;

use backend\modules\associate\models\forms\UpdateUserForm;
use common\functions\CommonFunctions;
use common\models\Users;
use Yii;
use yii\base\Exception;
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
        return $this->render('update-bank',
            ['user' => $user,
                'updateUserForm'=>$updateUserForm
            ]);
    }

    /** 下载协议模板 */
    public function actionDownload($type){
        if($type == 'agent'){
            $fileName = 'yundou_agent.pdf';
        }elseif($type == 'sale'){
            $fileName = 'yundou_sale.pdf';
        }else{
            throw new Exception("类型未定义");
        }
        if(file_exists('./agreement/'.$fileName)){
            return Yii::$app->response->sendFile('./agreement/'.$fileName);
        }else{
            return "<h1>文件不存在</h1>";
        }
    }
}