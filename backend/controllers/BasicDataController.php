<?php
namespace backend\controllers;

use backend\filters\AdminFilter;
use backend\filters\ManagerFilter;
use common\functions\CommonFunctions;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use common\models\Province;
use common\models\TestType;
use common\models\MajorJob;
use common\models\UsageMode;
use common\models\TestChapter;
use yii\data\Pagination;
use backend\filters\UserLoginFilter;

class BasicDataController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],[
                'class' => AdminFilter::className(),
            ],[
                'class' => ManagerFilter::className(),
            ]
        ];
    }

    public function  actionArea(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id = $request->post('id');
            $name = $request->post('name');
            if($id){
                $province = Province::findOne($id);
                CommonFunctions::createAlertMessage("修改成功","success");
            }else{
                $province = new Province();
                CommonFunctions::createAlertMessage("添加成功","success");
            }
            $province->name = $name;
            if(!$province->save()){
                throw new Exception("Basic Date area save error");
            }
            return $this->redirect(['basic-data/area']);
        }
        $models = Province::findAllForObject();
        return $this->render('area', [
            'models' => $models,
        ]);
    }

    public function  actionMajor(){
        $request = Yii::$app->request;
        if ($request->isPost) {
            $id = $request->post('id');
            $name = $request->post('name');
            if($id){
                $major = MajorJob::findOne($id);
                CommonFunctions::createAlertMessage("修改成功","success");
            }else{
                $major = new Province();
                CommonFunctions::createAlertMessage("添加成功","success");
            }
            $major->name = $name;
            if(!$major->save()){
                throw new Exception("Basic Date area save error");
            }
            return $this->redirect(['basic-data/major']);
        }
        $models = MajorJob::findAllForObject();
        return $this->render('major', [
            'models' => $models,
        ]);
    }

    public function  actionTestType(){
        $models = TestType::findAllForObject();
        return $this->render('test-type', [
            'models' => $models,
        ]);
    }

    public function  actionTestChapter(){
        $query = TestChapter::find()->where(['>','majorJobId',0]);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('test-chapter', [
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function  actionUsageMode(){
        $models = UsageMode::findAllForObject();
        return $this->render('usage-mode', [
            'models' => $models,
        ]);
    }

}