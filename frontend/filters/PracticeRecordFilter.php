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
        $leftBitcoin = $session->getFlash('leftBitcoin');
        $payBitcoin = $session->getFlash('payBitcoin');
        if($leftBitcoin || $payBitcoin){
            return parent::beforeAction($action);
        }
        $practiceRecord = PracticeRecord::findByUser($user['userId']);
        if(!$practiceRecord) {  //如果没有练习权
            $leftBitcoin = Users::findBitcoin($user['userId']);
            //获取支付方案
            /** @var $scheme \common\models\Scheme*/
            $scheme = Scheme::findOne(['type'=>1]);
            $session->set('practice-scheme',$scheme);   //存入session，在支付的时候使用
            $payBitcoin = $scheme->payBitcoin;
            $session->setFlash('leftBitcoin',$leftBitcoin); //剩余云豆
            $session->setFlash('payBitcoin',$payBitcoin);   //本次需要支付的云豆
            $url = Url::to(['practice/index',true]);
            header("Location:$url");
            return false;
        }
        return parent::beforeAction($action);
    }
}