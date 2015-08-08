<?php

namespace backend\controllers;

use Yii;
use common\models\TestLibrary;
use common\models\Province;
use common\models\TestType;
use common\models\MajorJob;
use backend\models\TestLirarySearch;
use yii\web\Controller;
use yii\data\Pagination;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TestLibraryController implements the CRUD actions for TestLibrary model.
 */
class TestLibraryController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all TestLibrary models.
     * @return mixed
     */
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

    /**
     * Displays a single TestLibrary model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TestLibrary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
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

    /**
     * Updates an existing TestLibrary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Deletes an existing TestLibrary model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TestLibrary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TestLibrary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TestLibrary::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
