<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use Yii;
use common\models\TestLibrary;
use common\models\Province;
use common\models\TestType;
use common\models\MajorJob;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;

class TestLibraryController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex()
    {
        $query = TestLibrary::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionSearch(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = $request->post('content');
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query){
            switch ($type) {
                case 'testType':
                    $table_a = TestLibrary::tableName();
                    $table_b = TestType::tableName();
                    $query = TestLibrary::find()
                        ->leftJoin($table_b, "$table_a.testTypeId=$table_b.testTypeId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'name':
                    $query = TestLibrary::find()
                        ->where(['like', $type, $content]);
                    break;
                case 'province':
                    $table_a = TestLibrary::tableName();
                    $table_b = Province::tableName();
                    $query = TestLibrary::find()
                        ->leftJoin($table_b, "$table_a.provinceId=$table_b.provinceId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'majorJob':
                    $table_a = TestLibrary::tableName();
                    $table_b = MajorJob::tableName();
                    $query = TestLibrary::find()
                        ->leftJoin($table_b, "$table_a.majorJobId='.$table_b.majorJobId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                default:
                    $query = TestLibrary::find();
                    break;
            }
        }
        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'models' => $model,
            'pages' => $pagination
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new TestLibrary();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->testLibraryId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->testLibraryId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = TestLibrary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
