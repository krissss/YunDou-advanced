<?php
/**
 * 在线练习
 */

namespace frontend\controllers;

use common\models\Collection;
use common\models\CurrentTestLibrary;
use common\models\ErrorQuestion;
use common\models\MajorJob;
use common\models\PracticeRecord;
use common\models\Users;
use frontend\filters\OpenIdFilter;
use frontend\filters\PracticeRecordFilter;
use frontend\filters\RegisterFilter;
use Yii;
use common\models\TestLibrary;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class PracticeController extends Controller
{
    public function behaviors(){
        return [
            'access' => [
                'class' => OpenIdFilter::className(),
            ],[
                'class' => RegisterFilter::className()
            ],[
                'class' => PracticeRecordFilter::className(),
                'only' => ['index','normal','single']
            ],
            /*'pageCache' => [
                'class' => 'yii\filters\PageCache',
                'only' => ['normal'],
                'duration' => 60,
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM testLibrary',
                ], 'variations' => [
                    Yii::$app->request->get('type')
                ],
            ],[
                'class' => 'yii\filters\PageCache',
                'only' => ['normal'],
                'duration' => 10,
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM testLibrary',
                ]
            ]*/
        ];
    }

    /**
     * 首页
     * @return string
     */
    public function actionIndex(){
        //首页获取collections，以备后面要用
        $session = Yii::$app->session;
        //$session->removeAll();
        $user = $session->get('user');
        $collections = Collection::findAllByUser($user['userId']);
        //以testLibraryId为索引，方便以后查key是否存在
        $collections = ArrayHelper::index($collections,'testLibraryId');
        $session->set('collections',$collections);
        return $this->render('index');
    }

    /**
     * 顺序练习
     * @param $type
     * @return string
     * @throws Exception
     */
    public function actionNormal($type){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $testTypeId = -1;   //-1表示全部练习
        $testTitle = "顺序练习";
        if($type == 'continue'){
            $currentNumber = TestLibrary::findCurrentNumber($user,$testTypeId);
        }elseif($type == 'restart'){
            $currentNumber = CurrentTestLibrary::resetCurrent($user,$testTypeId);
        }else{
            throw new Exception("practice/normal type does not defined");
        }
        //$testLibraries = TestLibrary::findByUserAndTestType($user,$testTypeId,50,$currentNumber);
        $testLibraries = TestLibrary::findAllByUserAndTestType($user,$testTypeId);
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'currentNumber' => $currentNumber
        ]);
    }

    /**
     * 单项训练
     * @param $type
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
        $currentNumber = TestLibrary::findCurrentNumber($user,$testTypeId);
        //$totalNumber = TestLibrary::findTotalNumber($user,$testTypeId);
        //$testLibraries = TestLibrary::findByUserAndTestType($user,$testTypeId,70,$currentNumber);
        $testLibraries = TestLibrary::findAllByUserAndTestType($user,$testTypeId);
        //测试图片显示的$testLibraries
        //$testLibraries = TestLibrary::find()->where('pictureSmall is not null')->orWhere('pictureBig is not null')->andWhere(['majorJobId'=>4,'testTypeId'=>1])->all();
        //测试案例题为多选
        //$testLibraries = TestLibrary::find()->where('LENGTH(answer) > 10')->andWhere(['majorJobId'=>4,'testTypeId'=>4])->all();
        if(count($testLibraries)==0){
            echo "<h1>题库建设中</h1>";
            exit;
        }
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('totalNumber',count($testLibraries)); //总题数
        //$session->set('totalNumber',$totalNumber); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'currentNumber' => $currentNumber
        ]);
    }

    /**
     * 练习模式下一题点击后操作
     * @throws Exception
     */
    public function actionNext(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $session = Yii::$app->session;
            $testLibraryId = $request->post('testLibraryId');
            $type = $request->post('type');
            $testTypeId = $session->get('testTypeId');
            $user = $session->get('user');
            if($testTypeId==-1||$testTypeId==1||$testTypeId==2||$testTypeId==3||$testTypeId==4){    //只有这五种练习方式记录当前练习到哪一题
                CurrentTestLibrary::saveOrUpdate($user['userId'],$testTypeId,$testLibraryId);
            }
            if($type == "wrong"){
                ErrorQuestion::saveOrUpdate($user['userId'],$testLibraryId);
            }
        }else{
            throw new Exception("非法提交");
        }
    }

    /**
     * 错题练习
     * @return string
     */
    public function actionWrongTest(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $testLibraries = ErrorQuestion::findAllByUserWithTestLibrary($user['userId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTitle',"错题练习");    //测试标题
        $session->set('majorJob',$user['nickname']);    //测试岗位使用用户昵称
        $session->set('testType',6);    //测试类型，6表示错题练习
        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'currentNumber' => 0
        ]);
    }

    /**
     * 重点题练习
     * @return string
     */
    public function actionCollectionTest(){
        $session = Yii::$app->session;
        $user = $session->get('user');
        $testLibraries = Collection::findAllByUserWithTestLibrary($user['userId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('totalNumber',count($testLibraries)); //总题数
        $session->set('testTitle',"错题练习");    //测试标题
        $session->set('majorJob',$user['nickname']);    //测试岗位使用用户昵称
        $session->set('testType',7);    //测试类型，7表示重点题练习
        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'currentNumber' => 0
        ]);
    }

    public function actionOver(){
        $request = Yii::$app->request;
        if($request->isPost){
            $result = $request->post('result');
            $result = json_decode($result,true);
            //重新以testLibraryId索引数组，确保同testLibraryId只存在最后一个数据
            $result = ArrayHelper::index($result,'testLibraryId');
            $rightNumber = 0;
            $wrongNumber = 0;
            foreach($result as $r){
                if($r['answerType'] == 1){
                    $rightNumber++;
                }else{
                    $wrongNumber++;
                }
            }
            return $this->render('over',[
                'rightNumber'=>$rightNumber,
                'wrongNumber'=>$wrongNumber
            ]);
        }else{
            throw new Exception("非法提交");
        }
    }

    /**
     * 收藏
     * @return string
     * @throws Exception
     */
    public function actionCollection(){
        $request = Yii::$app->request;
        if($request->isAjax) {
            $session = Yii::$app->session;
            $testLibraryId = $request->post('testLibraryId');
            $user = $session->get('user');
            return Collection::saveOrDelete($user['userId'], $testLibraryId);
        }else{
            throw new Exception("非法提交");
        }
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