<?php

namespace frontend\controllers;

use common\models\ExamTemplate;
use common\models\ExamTemplateDetail;
use common\models\MajorJob;
use common\models\TestLibrary;
use common\models\Users;
use Yii;
use yii\helpers\Url;
use yii\web\Controller;

class ExamController extends Controller
{
    public $layout = 'practice';

    public function actionIndex(){
        $openId = Yii::$app->request->get('openId');
        $session = Yii::$app->session;
        if($openId){    //存在表明来自微信端点击链接
            $session->removeAll();  //清空session，保证以后的所有操作均从空的session开始
            $user = Users::findByWeiXin($openId);
            $session->set('user',$user);
            if($user['majorJobId']==0){ //判断用户是否经过实名认证
                Url::remember();    //记住当前url地址，注册后跳转
                return $this->redirect(['account/register']);
            }
        }
        $user = $session->get('user');
        $examTemplates = ExamTemplate::findByMajorJobAndProvince($user['majorJobId'],$user['provinceId']);
        $totalNumber = count($examTemplates);
        if($totalNumber == 0){
            return "没有模拟试卷";
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
}