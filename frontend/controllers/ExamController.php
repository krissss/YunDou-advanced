<?php

namespace frontend\controllers;

use common\models\ExamTemplate;
use common\models\ExamTemplateDetail;
use common\models\MajorJob;
use common\models\TestLibrary;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\filters\RegisterFilter;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class ExamController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],[
                'class' => RegisterFilter::className()
            ]
        ];
    }

    public function actionIndex(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $examTemplates = ExamTemplate::findByMajorJobAndProvince($user['majorJobId'],$user['provinceId']);
        $totalNumber = count($examTemplates);
        if($totalNumber == 0){
            return "<h1>没有模拟试卷</h1>";
        }
        $rand = rand(1,$totalNumber);
        $examTemplate = $examTemplates[$rand-1];
        $session->set('examTemplate',$examTemplate);    //存入session,在考试结束后计算分数要用到
        $examTemplateDetails = ExamTemplateDetail::findByExamTemplate($examTemplate['examTemplateId']);
        $examTemplateDetails = ExamTemplateDetail::remakeArray($examTemplateDetails);
        $testLibraries = TestLibrary::findByTemplateDetails($examTemplateDetails,$user);
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries);  //所有同类型题目
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTitle',"模拟考试");    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位
        return $this->render('index',[
            'testLibraries' => $testLibraries
        ]);
    }

    public function actionOver(){
        $request = Yii::$app->request;
        if($request->isPost){
            $result = $request->post('result');
            $time = $request->post('time');
            $result = json_decode($result,true);
            //重新以testLibraryId索引数组，确保同testLibraryId只存在最后一个数据
            $result = ArrayHelper::index($result,'testLibraryId');
            $examTemplate = Yii::$app->session->get('examTemplate');

            $examTemplatePa1 = explode('|',$examTemplate['pa1']);
            $number_pa1 = $examTemplatePa1[0];
            $score_pa1 = $examTemplatePa1[1];
            if($number_pa1!=0){
                $singleScore_pa1 = $score_pa1/$number_pa1;
            }else{
                $singleScore_pa1 = 0;
            }
            $examTemplatePa2 = explode('|',$examTemplate['pa2']);
            $number_pa2 = $examTemplatePa2[0];
            $score_pa2 = $examTemplatePa2[1];
            if($number_pa2!=0){
                $singleScore_pa2 = $score_pa2/$number_pa2;
            }else{
                $singleScore_pa2 = 0;
            }
            $examTemplatePa3 = explode('|',$examTemplate['pa3']);
            $number_pa3 = $examTemplatePa3[0];
            $score_pa3 = $examTemplatePa3[1];
            if($number_pa3!=0){
                $singleScore_pa3 = $score_pa3/$number_pa3;
            }else{
                $singleScore_pa3 = 0;
            }
            $examTemplatePa4 = explode('|',$examTemplate['pa4']);
            $number_pa4 = $examTemplatePa4[0];
            $score_pa4 = $examTemplatePa4[1];
            if($number_pa4!=0){
                $singleScore_pa4 = $score_pa4/$number_pa4/5;
            }else{
                $singleScore_pa4 = 0;
            }

            $examTemplatePb1 = explode('|',$examTemplate['pb1']);
            $number_pb1 = $examTemplatePb1[0];
            $score_pb1 = $examTemplatePb1[1];
            if($number_pb1!=0){
                $singleScore_pb1 = $score_pb1/$number_pb1;
            }else{
                $singleScore_pb1 = 0;
            }
            $examTemplatePb2 = explode('|',$examTemplate['pb2']);
            $number_pb2 = $examTemplatePb2[0];
            $score_pb2 = $examTemplatePb2[1];
            if($number_pb2!=0){
                $singleScore_pb2 = $score_pb2/$number_pb2;
            }else{
                $singleScore_pb2 = 0;
            }
            $examTemplatePb3 = explode('|',$examTemplate['pb3']);
            $number_pb3 = $examTemplatePb3[0];
            $score_pb3 = $examTemplatePb3[1];
            if($number_pb3!=0){
                $singleScore_pb3 = $score_pb3/$number_pb3;
            }else{
                $singleScore_pb3 = 0;
            }
            $examTemplatePb4 = explode('|',$examTemplate['pb4']);
            $number_pb4 = $examTemplatePb4[0];
            $score_pb4 = $examTemplatePb4[1];
            if($number_pb4!=0){
                $singleScore_pb4 = $score_pb4/$number_pb4/5;
            }else{
                $singleScore_pb4 = 0;
            }

            $totalScore = $score_pa1+$score_pa2+$score_pa3+$score_pa4+
                          $score_pb1+$score_pb2+$score_pb3+$score_pb4;
            $finalScore = 0;
            foreach($result as $r){
                if($r['answerType'] == 1) { //答案正确
                    if ($r['preType'] == 1) {   //专业基础
                        switch ($r['testType']) {   //根据测试类型获得分数
                            case 1:
                                $finalScore += $singleScore_pa1;
                                break;
                            case 2:
                                $finalScore += $singleScore_pa2;
                                break;
                            case 3:
                                $finalScore += $singleScore_pa3;
                                break;
                            case 4:
                                $finalScore += $singleScore_pa4;
                                break;
                        }
                    } elseif ($r['preType'] == 2) { //管理实务
                        switch ($r['testType']) {   //根据测试类型获得分数
                            case 1:
                                $finalScore += $singleScore_pb1;
                                break;
                            case 2:
                                $finalScore += $singleScore_pb2;
                                break;
                            case 3:
                                $finalScore += $singleScore_pb3;
                                break;
                            case 4:
                                $finalScore += $singleScore_pb4;
                                break;
                        }
                    }
                }
            }
            return $this->render('over',[
                'time' => $time,
                'totalScore' => $totalScore,
                'finalScore' => $finalScore
            ]);
        }else{
            throw new Exception("非法提交");
        }
    }
}