<?php
/**
 * 用户是否登录的过滤器
 */

namespace backend\filters;

use Yii;
use yii\helpers\Url;
use yii\base\ActionFilter;

class UserLoginFilter extends ActionFilter{
    public $user = false;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->getSession()->get('user');
    }

    public function beforeAction($action){
        Yii::$app->getSession()->set('loginUrl',Yii::$app->request->getUrl());  //设置登陆后许跳转的页面
        if($this->user){
            return parent::beforeAction($action);
        }
        Yii::$app->getResponse()->redirect(Url::to(['site/login']));
        return false;
    }
}