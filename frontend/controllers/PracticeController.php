<?php
/**
 * 在线练习
 */

namespace frontend\controllers;

use common\models\CurrentTestLibrary;
use common\models\ErrorQuestion;
use common\models\MajorJob;
use common\models\Users;
use Yii;
use common\models\TestLibrary;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;

class PracticeController extends Controller
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
        return $this->render('index');
    }

    /**
     * 顺序练习
     * @param $type
     * @param $openId
     * @return string
     * @throws Exception
     */
    public function actionNormal($type){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $testTypeId = -1;   //-1表示全部练习
        $testTitle = "顺序练习";
        if($type == 'continue'){
            $next = CurrentTestLibrary::findTestLibraryIdByUserAndTestType($user,$testTypeId);
        }elseif($type == 'restart'){
            $next = CurrentTestLibrary::resetCurrent($user,$testTypeId);
        }else{
            throw new Exception("practice/normal type cannot defined");
        }
        $testLibraries = TestLibrary::findAllByUserAndTestType($user,$testTypeId);
        //以testLibraryId作为数组索引,将所有同类型的题目存入session
        $testLibraries = ArrayHelper::index($testLibraries,'testLibraryId');
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries);  //所有同类型题目
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位
        $questionNumber = 1;
        $firstNumber = TestLibrary::findFirstByUserAndTestType($user,$testTypeId)['testLibraryId'];
        for($i=$firstNumber; $i<$next; $i++){
            if (array_key_exists($i, $testLibraries)) {
                $questionNumber++;
            }
        }

        return $this->render('test',[
            'testLibrary' => $testLibraries[$next],
            'questionNumber' => $questionNumber
        ]);
    }

    /**
     * 单项训练
     * @param $type
     * @param $openId
     * @return string
     */
    public function actionSingle($type){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $testTypeId = 0;
        $testTitle = "单项练习-";
        switch($type){
            case 'danxuan':
                $testTypeId = 1;
                $testTitle.="单选题";
                break;
            case 'duoxuan':
                $testTypeId = 2;
                $testTitle.="多选题";
                break;
            case 'panduan':
                $testTypeId = 3;
                $testTitle.="判断题";
                break;
            case 'anli':
                $testTypeId = 4;
                $testTitle.="案例计算题";
                break;
            default:
                break;
        }
        $testLibraries = TestLibrary::findAllByUserAndTestType($user,$testTypeId);
        //以testLibraryId作为数组索引,将所有同类型的题目存入session
        $testLibraries = ArrayHelper::index($testLibraries,'testLibraryId');
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries);  //所有同类型题目
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位
        //得到用户上一次做到哪一题
        $next = CurrentTestLibrary::findTestLibraryIdByUserAndTestType($user,$testTypeId);
        //计算题目编号
        $questionNumber = 1;
        $firstNumber = TestLibrary::findFirstByUserAndTestType($user,$testTypeId)['testLibraryId'];
        for($i=$firstNumber; $i<$next; $i++){
            if (array_key_exists($i, $testLibraries)) {
                $questionNumber++;
            }
        }

        return $this->render('test',[
            'testLibrary' => $testLibraries[$next],
            'questionNumber' => $questionNumber
        ]);
    }

    /**
     * 出下一题，URL地址栏中next显示的为上一题的编号
     * @param $next
     * @return string
     */
    public function actionSingleNext($next){
        $session = Yii::$app->session;
        $testLibraries = $session->get('testLibraries');
        $max = end($testLibraries)['testLibraryId'];
        //获取下一题
        for($next++ ; $next<=$max; $next++) {
            if (array_key_exists($next, $testLibraries)) {
                break;
            }
        }
        //判断是否没有下一题
        if($next > $max){
            //单项结束//TODO
            return 'all over';
        }
        //下一题
        $nextTestLibrary = $testLibraries[$next];
        //计算题目编号
        $questionNumber = 1;
        for($i=0; $i<$next; $i++){
            if (array_key_exists($i, $testLibraries)) {
                $questionNumber++;
            }
        }

        return $this->render('test',[
            'testLibrary' => $nextTestLibrary,
            'questionNumber' => $questionNumber,
        ]);
    }

    public function actionSingleSave(){
        $request = Yii::$app->request;
        $session = Yii::$app->session;
        $testLibraryId = $request->post('testLibraryId');
        $type = $request->post('type');
        $testTypeId = $session->get('testTypeId');
        $user = $session->get('user');
        //如果答案正确，保存当前进度
        if($type == 'right'){
            if(CurrentTestLibrary::saveOrUpdate($user['userId'],$testTypeId,$testLibraryId)){
                return 'success';
            }
        }
        //如果答案错误，保存错题，保存当前进度
        if($type == 'wrong'){
            if(ErrorQuestion::saveOrUpdate($user['userId'],$testLibraryId)){
                if(CurrentTestLibrary::saveOrUpdate($user['userId'],$testTypeId,$testLibraryId)){
                    return 'success';
                }
            }
        }
        //既不是正确也不是错误，正常情况下不出现
        return 'practice/single-save type undefined';
    }

}