<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\models\TestChapter;
use Yii;
use common\models\TestLibrary;
use common\models\Province;
use common\models\TestType;
use common\models\MajorJob;
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
                case 'question':
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

    public function actionUpdate($id){
        return "暂未开发";
    }
}
