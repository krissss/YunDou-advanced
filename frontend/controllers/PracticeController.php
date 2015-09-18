<?php
/**
 * 在线练习
 */

namespace frontend\controllers;

use common\models\Collection;
use common\models\CurrentTestLibrary;
use common\models\ErrorQuestion;
use common\models\MajorJob;
use frontend\filters\OpenIdFilter;
use frontend\filters\PracticeRecordFilter;
use frontend\filters\RegisterFilter;
use Yii;
use common\models\TestLibrary;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\helpers\Url;

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
                'duration' => 10,
                'dependency' => [
                    'class' => 'yii\caching\DbDependency',
                    'sql' => 'SELECT COUNT(*) FROM testLibrary',
                ]
            ],*/
            /*'httpCache'=> [
                'class' => 'yii\filters\HttpCache',
                'only' => ['normal'],
                'lastModified' => function ($action, $params) {
                    $q = new \yii\db\Query();
                    return $q->from('testLibrary')->count();
                },
            ],*/
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
        if($currentNumber >= count($testLibraries)){ //当前题号达到最大题号，重新从头开始
            $currentNumber = 0;
        }
        $countNumber = count($testLibraries);
        if($countNumber == 0){  //避免没有题目生成出题页面出错
            $url = Url::to(['site/test-library-not-found']);
            header("Location:$url");
            exit;
        }
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries); //所有题目
        $session->set('totalNumber',$countNumber); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位

        //取出特定的题目量,$testLibraries为引用
        $startNumber = TestLibrary::takeOutSpecialNumber($currentNumber,$testLibraries);

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'startNumber' => $startNumber,  //页面上的题目准确的开始题号
            'currentNumber' => $currentNumber   //用户做到题库中的第几题
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
        $testLibraries = TestLibrary::findAllByUserAndTestType($user,$testTypeId);
        if($currentNumber >= count($testLibraries)){ //当前题号达到最大题号，重新从头开始
            $currentNumber = 0;
        }
        //测试图片
        //$testLibraries = TestLibrary::find()->where('pictureBig is not null')->orWhere('pictureSmall is not null')->all();
        $countNumber = count($testLibraries);
        if($countNumber == 0){  //避免没有题目生成出题页面出错
            $url = Url::to(['site/test-library-not-found']);
            header("Location:$url");
            exit;
        }
        $majorJob = MajorJob::findNameByMajorJobId($user['majorJobId']);
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries); //所有题目
        $session->set('totalNumber',$countNumber); //总题数
        $session->set('testTypeId',$testTypeId);    //测试类型id
        $session->set('testTitle',$testTitle);    //测试标题
        $session->set('majorJob',$majorJob);    //测试岗位

        //取出特定的题目量,$testLibraries为引用
        $startNumber = TestLibrary::takeOutSpecialNumber($currentNumber,$testLibraries);

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'startNumber' => $startNumber,  //页面上的题目准确的开始题号
            'currentNumber' => $currentNumber   //用户做到题库中的第几题
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
            if($type == 0){
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
        $countNumber = count($testLibraries);
        if($countNumber == 0){  //避免没有题目生成出题页面出错
            $url = Url::to(['site/test-library-wrong-zero']);
            header("Location:$url");
            exit;
        }
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries); //所有题目
        $session->set('totalNumber',$countNumber); //总题数
        $session->set('testTitle',"错题练习");    //测试标题
        $session->set('majorJob',$user['nickname']);    //测试岗位使用用户昵称
        $session->set('testType',6);    //测试类型，6表示错题练习

        //取出特定的题目量
        $defaultOnceNumber = Yii::$app->params['defaultOnceNumber'];
        $testLibraries = array_slice($testLibraries,0,$defaultOnceNumber);

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'startNumber' => 0,  //页面上的题目准确的开始题号
            'currentNumber' => 0   //用户做到题库中的第几题
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
        $countNumber = count($testLibraries);
        if($countNumber == 0){  //避免没有题目生成出题页面出错
            $url = Url::to(['site/test-library-collection-zero']);
            header("Location:$url");
            exit;
        }
        //将一些必要参数存入session，方便后续页面调用
        $session->set('testLibraries',$testLibraries); //所有题目
        $session->set('totalNumber',$countNumber); //总题数
        $session->set('testTitle',"错题练习");    //测试标题
        $session->set('majorJob',$user['nickname']);    //测试岗位使用用户昵称
        $session->set('testType',7);    //测试类型，7表示重点题练习

        //取出特定的题目量
        $defaultOnceNumber = Yii::$app->params['defaultOnceNumber'];
        $testLibraries = array_slice($testLibraries,0,$defaultOnceNumber);

        return $this->render('test',[
            'testLibraries' => $testLibraries,
            'startNumber' => 0,  //页面上的题目准确的开始题号
            'currentNumber' => 0   //用户做到题库中的第几题
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

    /** ajax 添加后面的题目 */
    public function actionGetData(){
        $request = Yii::$app->request;
        if($request->isAjax){
            $session = Yii::$app->session;
            $testLibraries = $session->get('testLibraries');
            $defaultOnceNumber = Yii::$app->params['defaultOnceNumber'];
            $minNumber = $request->post('minNumber');
            $maxNumber = $request->post('maxNumber');
            if($minNumber!=0){
                if($minNumber-$defaultOnceNumber<=0){
                    return $this->renderAjax('append',[
                        'testLibraries' => array_slice($testLibraries,0,$minNumber-1),
                        'startNumber' => 0,  //页面上的题目准确的开始题号
                    ]);
                }else{
                    return $this->renderAjax('append',[
                        'testLibraries' => array_slice($testLibraries,$minNumber-$defaultOnceNumber-1,$defaultOnceNumber),
                        'startNumber' => $minNumber-$defaultOnceNumber-1,  //页面上的题目准确的开始题号
                    ]);
                }
            }elseif($maxNumber){
                $totalNumber = count($testLibraries);
                if($maxNumber+$defaultOnceNumber>=$totalNumber){
                    return $this->renderAjax('append',[
                        'testLibraries' => array_slice($testLibraries,$maxNumber,$totalNumber-$maxNumber),
                        'startNumber' => $maxNumber,  //页面上的题目准确的开始题号
                    ]);
                }else{
                    return $this->renderAjax('append',[
                        'testLibraries' => array_slice($testLibraries,$maxNumber,$defaultOnceNumber),
                        'startNumber' => $maxNumber,  //页面上的题目准确的开始题号
                    ]);
                }
            }elseif($minNumber == 0){
                return true;
            }else{
                throw new Exception("参数错误");
            }
        }else{
            throw new Exception("非法提交");
        }
    }
}