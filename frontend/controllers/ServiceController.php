<?php
/** 咨询与建议 */
namespace frontend\controllers;

use common\functions\CommonFunctions;
use frontend\filters\OpenIdFilter;
use Yii;
use common\models\Service;
use frontend\models\forms\ConsultForm;
use yii\data\Pagination;
use yii\web\Controller;

class ServiceController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ]
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
            CommonFunctions::createAlertMessage("请填写相关咨询的内容或者对我们的建议，我们会在第一时间回复您！","info");
        }
        $ownerServices = Service::findByUser($user['userId'],5);
        $publishServices = Service::findPublished(5);
        return $this->render('consult',[
            'consultForm' => $consultForm,
            'ownerServices' => $ownerServices,
            'publishServices' => $publishServices
        ]);
    }

    public function actionSelf(){
        $user = Yii::$app->session->get('user');
        $query = Service::find()
            ->where(['userId'=>$user['userId']])
            ->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('self', [
            'models' => $models,
            'pages' => $pagination
        ]);
    }

    public function actionOther(){
        $query = Service::find()
            ->where(['state' => Service::STATE_PUBLISH])
            ->orderBy(['createDate'=>SORT_DESC]);
        $pagination = new Pagination([
            'defaultPageSize' => 20,
            'totalCount' => $query->count(),
        ]);
        $models = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('other', [
            'models' => $models,
            'pages' => $pagination
        ]);
    }

    /** 咨询与建议查看 */
    public function actionView($id){
        $service = Service::findOne($id);
        return $this->render('view',[
            'service' => $service
        ]);
    }
}