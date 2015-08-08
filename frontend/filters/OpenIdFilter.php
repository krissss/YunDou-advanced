<?php

namespace frontend\filters;

use yii\helpers\Url;
use Yii;
use yii\base\ActionFilter;

class OpenIdFilter extends ActionFilter
{

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action){
        $session = Yii::$app->session;
        if($session->get('openId')){
            return parent::beforeAction($action);
        }else{
            $currentUrl = urlencode(Url::to(['account/index'],true));
            $appid_verify = md5("wxcf0cd66d7cdf07088bcd5a776d588ff7dc2f66c10b7efd11");
            $url = "http://www.weixingate.com/gate.php?back=$currentUrl&force=1&appid=wxcf0cd66d7cdf0708&appid_verify=$appid_verify";
            header("Location:$url");
            return false;
        }
    }
}