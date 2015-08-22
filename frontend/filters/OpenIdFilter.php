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
        //$session->removeAll();
        $session->set('openId','ow-bOvjH7CpKQtxsvjJuRmg6-g-k');
        if($user = $session->get('user')){
            return parent::beforeAction($action);
        }
        if($openId = $session->get('openId')){
            $session->set('user',Users::findByWeiXin($openId));
            return parent::beforeAction($action);
        }
        $request = Yii::$app->request;
        $state = $request->get("state");
        $current_url = Url::current([],true);   //认证后跳转至当前网址
        if($state == 'YUN'){    //需要与getAuthorizeUrl($redirect_uri)中定义的一致，用户认证后回调操作如下
            $code = $request -> get("code");
            if($code){  //code存在表示用户允许授权
                $result = WeiXinFunctions::getAuthAccessToken($code);
                $openId = $result->openid;
                $access_token = $result->access_token;
                $session->set('openId',$openId);
                header("Location:$current_url");    //授权后需重新跳转到当前网页
                return false;
            }else{
                echo "用户不允许授权";
                return false;
            }
        }
        $redirect_uri = urlencode($current_url);   //认证后跳转至当前网址
        $url = WeiXinFunctions::getAuthorizeUrl($redirect_uri);
        header("Location:$url");
        return false;
    }
}