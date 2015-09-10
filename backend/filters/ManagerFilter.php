<?php
/** 经理的过滤器 */

namespace backend\filters;

use common\models\Users;
use Yii;
use yii\base\Exception;
use yii\base\ActionFilter;

class ManagerFilter extends ActionFilter{
    public $user = false;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->getSession()->get('user');
    }

    public function beforeAction($action){
        if($this->user['role'] >= Users::ROLE_MANAGER){
            return parent::beforeAction($action);
        }
        throw new Exception("没有访问权限");
    }
}