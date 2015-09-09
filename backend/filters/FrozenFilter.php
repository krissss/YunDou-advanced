<?php
/** 冻结的用户过滤器 */

namespace backend\filters;

use common\models\Users;
use Yii;
use yii\helpers\Url;
use yii\base\ActionFilter;

class FrozenFilter extends ActionFilter{
    public $user = false;

    public function init()
    {
        parent::init();
        $this->user = Yii::$app->getSession()->get('user');
    }

    public function beforeAction($action){
        if($this->user['state']!=Users::STATE_FROZEN){
            return parent::beforeAction($action);
        }
        Yii::$app->getResponse()->redirect(Url::to(['/site/index']));
        return false;
    }
}