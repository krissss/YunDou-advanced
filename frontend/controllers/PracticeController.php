<?php
/**
 * 在线练习
 */

namespace frontend\controllers;

use common\models\CurrentTestLibrary;
use Yii;
use common\models\TestLibrary;
use yii\web\Controller;
use frontend\Classes\PracticeParamClass as PPC;

class PracticeController extends Controller
{

    public $layout = 'practice';

    public function actionIndex()
    {
        return $this->render('index');
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