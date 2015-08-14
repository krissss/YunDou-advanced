<?php

namespace backend\controllers;

use backend\filters\UserLoginFilter;
use common\functions\CommonFunctions;
use common\models\ExamTemplateDetail;
use common\models\MajorJob;
use common\models\Province;
use Yii;
use common\models\ExamTemplate;
use common\models\TestChapter;
use yii\base\Exception;
use yii\data\Pagination;
use yii\web\Controller;

class ExamTemplateController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => UserLoginFilter::className(),
            ],
        ];
    }

    public function actionIndex(){
        $session = Yii::$app->session;
        if(!$session->get("provinces")){
            $session->set('provinces',Province::findAllForObject());
            $session->set('majorJobs',MajorJob::findAllForObject());
        }
        $query = ExamTemplate::find();
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $examTemplates = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'examTemplates' => $examTemplates,
            'pages' => $pagination
        ]);
    }

    public function actionCreateTemplate(){
        $request = Yii::$app->request;
        $user = Yii::$app->session->get('user');
        if($request->isPost){   //创建模板
            $majorJobId = $request->post('majorJobId');
            $provinceId = $request->post('provinceId');
            $name = $request->post('name');
            if(!$majorJobId || !$provinceId || !$name){ //不能创建
                CommonFunctions::createAlertMessage("存在未填字段，未能创建模板",'error');
                return $this->redirect(['exam-template/index']);
            }
            $id = ExamTemplate::saveOne($provinceId,$majorJobId,$name,$user['userId']);
            if($request->post('continue') == 'continue'){   //保存并继续添加题目
                return $this->redirect(['exam-template/create-detail','id'=>$id]);
            }else{
                CommonFunctions::createAlertMessage("模板创建成功，你可以点击编辑添加题目","success");
                return $this->redirect(['exam-template/index']);
            }
        } else {
            CommonFunctions::createAlertMessage("非正常请求，错误！", 'error');
        }
        return $this->redirect(['service/index']);
    }

    public function actionView($id){
        return $this->redirect(['exam-template/create-detail','id'=>$id]);
    }

    public function actionDelete($id){
        /** @var  $examTemplate \common\models\ExamTemplate */
        $examTemplate = ExamTemplate::findOne($id);
        if($examTemplate && $examTemplate->state==ExamTemplate::STATE_DISABLE){ //非启用状态才能删除
            ExamTemplateDetail::deleteAllByExamTemplateId($examTemplate->examTemplateId);
            $examTemplate->delete();
            CommonFunctions::createAlertMessage("删除模板成功",'success');
            return $this->redirect(['exam-template/index']);
        }else{
            CommonFunctions::createAlertMessage("模板启用状态下，删除模板出错",'error');
            return $this->redirect(['exam-template/index']);
        }
    }

    public function actionSearch(){
        $request = Yii::$app->request;
        $query = Yii::$app->session->getFlash('query');
        if($request->isPost){
            $type = $request->post('type');
            $content = trim($request->post('content'));
        }else{
            $type = $request->get('type');
            $content = trim($request->get('content'));
        }
        if($type || !$query){
            switch ($type) {
                case 'province':
                    $table_a = ExamTemplate::tableName();
                    $table_b = Province::tableName();
                    $query = ExamTemplate::find()
                        ->leftJoin($table_b, "$table_a.provinceId=$table_b.provinceId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'majorJob':
                    $table_a = ExamTemplate::tableName();
                    $table_b = MajorJob::tableName();
                    $query = ExamTemplate::find()
                        ->leftJoin($table_b, "$table_a.majorJobId=$table_b.majorJobId")
                        ->where(['like', "$table_b.name", $content]);
                    break;
                case 'name':case 'state':
                    $query = ExamTemplate::find()
                        ->where(['like', $type, $content]);
                    break;
                default:
                    $query = ExamTemplate::find();
                    break;
            }
        }
        Yii::$app->session->setFlash('query',$query);
        $pagination = new Pagination([
            'defaultPageSize' => Yii::$app->params['pageSize'],
            'totalCount' => $query->count(),
        ]);
        $examTemplates = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();
        return $this->render('index',[
            'examTemplates' => $examTemplates,
            'pages' => $pagination
        ]);
    }

    public function actionCreateDetail($id){
        $request = Yii::$app->request;
        /** @var  $examTemplate \common\models\ExamTemplate*/
        $examTemplate = ExamTemplate::findOne($id);
        if($request->isPost){
            //保存数据之前 删除所有原来的数据 且必须是模板未启用的状态下才能删除
            if($examTemplate->state == ExamTemplate::STATE_DISABLE){
                ExamTemplateDetail::deleteAllByExamTemplateId($examTemplate->examTemplateId);
            }else{
                CommonFunctions::createAlertMessage("模板启用状态下删除题目出错",'error');
                return $this->redirect(['exam-template/index']);
            }
            //保存题目数量和分数
            $examTemplate->pa1 = $request->post("number_pa1")."|".$request->post("score_pa1");
            $examTemplate->pa2 = $request->post("number_pa2")."|".$request->post("score_pa2");
            $examTemplate->pa3 = $request->post("number_pa3")."|".$request->post("score_pa3");
            $examTemplate->pa4 = $request->post("number_pa4")."|".$request->post("score_pa4");
            $examTemplate->pb1 = $request->post("number_pb1")."|".$request->post("score_pb1");
            $examTemplate->pb2 = $request->post("number_pb2")."|".$request->post("score_pb2");
            $examTemplate->pb3 = $request->post("number_pb3")."|".$request->post("score_pb3");
            $examTemplate->pb4 = $request->post("number_pb4")."|".$request->post("score_pb4");
            if(!$examTemplate->save()){
                CommonFunctions::createAlertMessage("题目保存出错",'error');
                return $this->redirect(['exam-template/index']);
            }
            $testChapters = TestChapter::find()->where(['majorJobId'=>$examTemplate->majorJobId])->all();
            foreach($testChapters as $testChapter){
                $testNumber_1 = $request->post("danxuan_".$testChapter->testChapterId);
                $testNumber_2 = $request->post("duoxuan_".$testChapter->testChapterId);
                $testNumber_3 = $request->post("panduan_".$testChapter->testChapterId);
                $testNumber_4 = $request->post("anli_".$testChapter->testChapterId);
                if($testNumber_1 && $testNumber_1!=0){
                    ExamTemplateDetail::saveOne($id,$testChapter->preTypeId,$testChapter->testChapterId,1,$testNumber_1);
                }
                if($testNumber_2 && $testNumber_2!=0){
                    ExamTemplateDetail::saveOne($id,$testChapter->preTypeId,$testChapter->testChapterId,2,$testNumber_2);
                }
                if($testNumber_3 && $testNumber_3!=0){
                    ExamTemplateDetail::saveOne($id,$testChapter->preTypeId,$testChapter->testChapterId,3,$testNumber_3);
                }
                if($testNumber_4 && $testNumber_4!=0){
                    ExamTemplateDetail::saveOne($id,$testChapter->preTypeId,$testChapter->testChapterId,4,$testNumber_4);
                }
            }
            CommonFunctions::createAlertMessage("模板组题完成",'success');
            return $this->redirect(['exam-template/index']);
        }
        $testChapters_1 = TestChapter::find()->where(['majorJobId'=>$examTemplate->majorJobId, 'preTypeId'=>1])->all();
        $testChapters_2 = TestChapter::find()->where(['majorJobId'=>$examTemplate->majorJobId, 'preTypeId'=>2])->all();
        $examTemplateDetails = ExamTemplateDetail::findByExamTemplate($examTemplate->examTemplateId);
        $examTemplateDetails = ExamTemplateDetail::remakeArray($examTemplateDetails);
        return $this->render('create-detail',[
            'examTemplate' => $examTemplate,
            'testChapters_1' => $testChapters_1,
            'testChapters_2' => $testChapters_2,
            'examTemplateDetails' => $examTemplateDetails
        ]);
    }

    public function actionChangeState(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $newState = $request->post('newState');
            $examTemplateId = intval($request->post('id'));
            if($newState == 'open'){
                $newState = ExamTemplate::STATE_ABLE;
                ExamTemplate::updateState($examTemplateId,$newState);
                return 'open';
            }elseif($newState == 'close'){
                $newState = ExamTemplate::STATE_DISABLE;
                ExamTemplate::updateState($examTemplateId,$newState);
                return 'close';
            }else{
                return '状态非法';
            }
        }else{
            throw new Exception('非ajax提交');
        }
    }

}