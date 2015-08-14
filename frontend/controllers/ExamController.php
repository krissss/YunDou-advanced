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
        $examTemplateDetails = ExamTemplateDetail::findByExamTemplate($examTemplates[$rand-1]['examTemplateId']);
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
            //TODO
            $score = 123;
            return $this->render('over',[
                'time' => $time,
                'score' => $score
            ]);
        }else{
            throw new Exception("非法提交");
        }
    }
}