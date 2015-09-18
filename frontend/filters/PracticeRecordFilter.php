<?php
/** 用户是否付费练习的过滤器 */

namespace frontend\filters;

use common\models\PracticeRecord;
use common\models\Scheme;
use common\models\TestLibrary;
use Yii;
use yii\base\ActionFilter;
use yii\helpers\Url;

class PracticeRecordFilter extends ActionFilter
{
    public function init()
    {
        parent::init();
    }

    public function beforeAction($action){
        $session = Yii::$app->session;
        $user = $session->get('user');
        if(!TestLibrary::checkIsExist($user)){
            $url = Url::to(['site/test-library-not-found']);
            header("Location:$url");
        }
        $practiceRecordFlag = $session->getFlash('practiceRecordFlag');
        if($practiceRecordFlag){    //支付方案如果已经生成直接显示过去
            return parent::beforeAction($action);
        }
        $practiceRecord = PracticeRecord::findByUser($user['userId']);
        if(!$practiceRecord) {  //如果没有练习权
            //获取在线练习支付方案
            /** @var $scheme \common\models\Scheme */
            $schemes = Scheme::findPracticeScheme();
            $session->set('practice-schemes',$schemes);   //存入session，在支付的时候使用
            $session->setFlash('practiceRecordFlag',true);  //支付方案生成的标志
            $url = Url::to(['practice/index',true]);
            header("Location:$url");
            return false;
        }
        return parent::beforeAction($action);
    }
}