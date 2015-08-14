<?php

namespace frontend\controllers;

use common\functions\CommonFunctions;
use frontend\filters\OpenIdFilter;
use Yii;
use common\models\Service;
use frontend\models\forms\ConsultForm;
use yii\web\Controller;

class ServiceController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],
        ];
    }

    /** 咨询与建议 */
    public function actionConsult(){
        $user = Yii::$app->session->get('user');
        $consultForm = new ConsultForm();
        if($consultForm->load(Yii::$app->request->post()) && $consultForm->validate()){
            $consultForm->record();
            CommonFunctions::createAlertMessage("已经记录您的咨询或建议，我们将尽快给您答复","success");
        }else{
            CommonFunctions::createAlertMessage("请填写咨询或建议内容，我们将在一段时间内给您回复","info");
        }
        $ownerServices = Service::findAllByUser($user['userId']);
        $publishServices = Service::findPublished();
        return $this->render('consult',[
            'consultForm' => $consultForm,
            'ownerServices' => $ownerServices,
            'publishServices' => $publishServices
        ]);
    }

    /** 咨询与建议查看 */
    public function actionView($id){
        $user = Yii::$app->session->get('user');
        $service = Service::findOne($id);
        $ownerServices = Service::findAllByUser($user['userId']);
        $publishServices = Service::findPublished();
        return $this->render('view',[
            'service' => $service,
            'ownerServices' => $ownerServices,
            'publishServices' => $publishServices
        ]);
    }

    /** 我要报名 */
    public function actionEnroll(){
        echo "<h1>我要报名，建设中。。。</h1>";
    }
}