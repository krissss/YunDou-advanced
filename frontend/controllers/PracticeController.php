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
use yii\web\Controller;
use frontend\Classes\PracticeParamClass as PPC;

class PracticeController extends Controller
{

    public $layout = 'practice';

    //public $enableCsrfValidation = false;

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 单项训练
     * @param $type
     * @return string
     */
    public function actionSingle($type){
        $session = Yii::$app->session;
        Yii::$app->session->set('user',Users::findOne(1));
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
        $testLibraries = TestLibrary::findOneByType($user['province'],$user['majorJobId'],$testTypeId);
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
        $next = CurrentTestLibrary::findTestLibraryIdByUserAndTestType($user['userId'],$testTypeId);
        //计算题目编号
        $questionNumber = 1;
        for($i=0; $i<$next; $i++){
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
            //单项结束
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

    public function actionDanxuan(){
        return $this->render('danxuan');
    }

    public function actionTest($type, $start)
    {
        $user = Yii::$app->session->get('user');
        //$userId = $user->userId;
        $userId = 1;

        $testLibrary = "";

        $where = [];
        $andWhere = [];
        $order = SORT_ASC;
        switch ($type) {
            case PPC::TYPE_SEQUENCE:
                switch ($start) {
                    case PPC::START_CONTINUE:
                        $currentTestLibraryId = CurrentTestLibrary::find()->select('testLibraryId')->where(['userId'=>$userId])->one();
                        if($currentTestLibraryId){
                            $testLibrary = TestLibrary::find()->where(['testLibraryId'=>$currentTestLibraryId])->one();
                        }else{
                            $testLibrary = TestLibrary::find()->asArray()->one();
                        }
                        break;
                    case PPC::START_RESTART:
                        $currentTestLibrary = CurrentTestLibrary::find()->where(['userId'=>$userId])->one();
                        $currentTestLibrary->testLibraryId = 1;

                        $currentTestLibraryId = 1;
                        $testLibrary = TestLibrary::find()->where(['testLibraryId'=>$currentTestLibraryId])->one();
                        break;
                }
                break;
            case PPC::TYPE_RANDOM:
                switch ($start) {
                    case 'undo':
                        //去查询已做题
                        $order = 'rand()';
                        break;
                    case 'total':
                        $order = 'rand()';
                        break;
                }
                break;
            case PPC::TYPE_SPECIAL:
                switch ($start) {
                    case 'danxuan':
                        $where = ['testTypeId'=>1];
                        break;
                    case 'duoxuan':
                        $where = ['testTypeId'=>2];
                        break;
                    case 'panduan':
                        $where = ['testTypeId'=>3];
                        break;
                    case 'anli':
                        $where = ['testTypeId'=>4];
                        break;
                }
                break;
            case PPC::TYPE_WRONG:
                switch ($start) {
                    case 'total':
                        $where = ['testTypeId'=>1];
                        break;
                    case 'danxuan':
                        break;
                    case 'duoxuan':
                        break;
                    case 'panduan':
                        break;
                    case 'anli':
                        break;
                }
                break;
        }
        //$testLibraries = TestLibrary::find()->where($where)->andWhere($andWhere)->orderBy($order)->all();
        return $this->render('test', [
            'testLibrary' => $testLibrary
        ]);
    }

}