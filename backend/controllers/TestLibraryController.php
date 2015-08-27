<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use backend\models\forms\UpdateTestLibraryForm;
use common\models\TestChapter;
use Yii;
use common\models\TestLibrary;
use common\models\Province;
use common\models\TestType;
use common\models\MajorJob;
use yii\base\Exception;
use yii\web\Controller;
use yii\data\Pagination;

class TestLibraryController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex(){
        $query = TestLibrary::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $model = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->orderBy(['testLibraryId'=>SORT_DESC])
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
                case 'testLibraryId':
                    $query = TestLibrary::find()
                        ->where(['testLibraryId'=>$content]);
                    break;
                case 'testType':
                    $table_a = TestLibrary::tableName();
                    $table_b = TestType::tableName();
                    $query = TestLibrary::find()
                        ->leftJoin($table_b, "$table_a.testTypeId=$table_b.testTypeId")
                        ->where(['like', "$table_b.name", $content]);
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
                        ->leftJoin($table_b, "$table_a.majorJobId=$table_b.majorJobId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                case 'testChapter':
                    $table_a = TestLibrary::tableName();
                    $table_b = TestChapter::tableName();
                    $query = TestLibrary::find()
                        ->leftJoin($table_b, "$table_a.testChapterId=$table_b.testChapterId")
                        ->where(['like', $table_b . ".name", $content]);
                    break;
                case 'question': case 'problem': case 'options':
                    $query = TestLibrary::find()
                        ->where(['like', $type, $content]);
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

    public function actionUpdate(){
        $request = Yii::$app->request;
        if($request->isPost){
            $testLibraryId = $request->post('testLibraryId');
            $updateTestLibraryForm = new UpdateTestLibraryForm();
            $updateTestLibraryForm->initWithId($testLibraryId);
            return $this->renderAjax('update',[
                'updateTestLibraryForm' => $updateTestLibraryForm
            ]);
        }else{
            throw new Exception("非正常请求");
        }
    }

    public function actionGenerate(){
        $request = Yii::$app->request;
        if($request->isPost){
            $updateTestLibraryForm = new UpdateTestLibraryForm();
            if($updateTestLibraryForm->load($request->post()) && $updateTestLibraryForm->validate()){
                $updateTestLibraryForm->update();
                return $this->redirect(['test-library/index']);
            }
            throw new Exception("验证出错");
        }else{
            throw new Exception("非正常请求");
        }
    }
}
