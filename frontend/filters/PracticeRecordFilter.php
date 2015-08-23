<?php
/** 用户是否付费练习的过滤器 */

namespace frontend\filters;

use common\models\PracticeRecord;
use common\models\Scheme;
use common\models\Users;
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
        $practiceRecordFlag = $session->getFlash('practiceRecordFlag');
        if($practiceRecordFlag){    //支付方案如果已经生成直接显示过去
            return parent::beforeAction($action);
        }
        $practiceRecord = PracticeRecord::findByUser($user['userId']);
        if(!$practiceRecord) {  //如果没有练习权
            $leftBitcoin = Users::findBitcoin($user['userId']);
            //获取在线练习支付方案
            /** @var $scheme \common\models\Scheme */
            $scheme = Scheme::findPracticeScheme();
            $session->set('practice-scheme',$scheme);   //存入session，在支付的时候使用
            $session->setFlash('leftBitcoin',$leftBitcoin); //剩余云豆
            $session->setFlash('practiceRecordFlag',true);  //支付方案生成的标志
            $url = Url::to(['practice/index',true]);
            header("Location:$url");
            return false;
        }
        return parent::beforeAction($action);
    }
}