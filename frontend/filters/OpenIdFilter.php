<?php

namespace frontend\filters;

use common\models\Users;
use frontend\functions\WeiXinFunctions;
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
        }
        $request = Yii::$app->request;
        $state = $request->get("state");
        if($state == 'YUN'){    //需要与getAuthorizeUrl($redirect_uri)中定义的一致，用户认证后回调操作如下
            $code = $request -> get("code");
            if($code){  //code存在表示用户允许授权
                $result = WeiXinFunctions::getAuthAccessToken($code);
                $openId = $result->openid;
                $access_token = $result->access_token;
                $userInfo = Users::findByWeiXin($openId);
                $session->set('openId',$openId);
                return parent::beforeAction($action);
            }else{
                echo "用户不允许授权";
                return false;
            }
        }
        $redirect_uri = urlencode(Url::to(["account/register"],true));
        $url = WeiXinFunctions::getAuthorizeUrl($redirect_uri);
        header("Location:$url");
        return false;
        /*$request = Yii::$app->request;
        if($request->get('openId')){
            return parent::beforeAction($action);
        }else{
            $currentUrl = urlencode(Url::to(['account/register'],true));
            $appid_verify = md5("wxcf0cd66d7cdf07088bcd5a776d588ff7dc2f66c10b7efd11");
            $url = "http://www.weixingate.com/gate.php?back=$currentUrl&force=1&appid=wxcf0cd66d7cdf0708&appid_verify=$appid_verify";
            header("Location:$url");
            return false;
        }*/
    }
}