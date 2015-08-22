<?php

namespace frontend\filters;

use yii\helpers\Url;
use Yii;
use yii\base\ActionFilter;

class RegisterFilter extends ActionFilter
{
    public function init()
    {
        parent::init();
    }

    public function beforeAction($action){
        $session = Yii::$app->session;
        $user = $session->get('user');
        if(!$user){
            return false;
        }
        if($user['majorJobId'] == 0 || $user['registerDate']==0){
            Url::remember("register");    //记住当前url地址，注册后跳转
            $url = Url::to(["account/register"],true);
            header("Location:$url");
            return false;
        }else{
            return parent::beforeAction($action);
        }
    }
}