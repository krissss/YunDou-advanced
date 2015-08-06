<?php

namespace backend\widgets;

use Yii;
use yii\base\Widget;

class AlertWidget extends Widget
{
    private $message; //消息
    private $message_type; //消息类型

    public function init()
    {
        parent::init();
        $session = Yii::$app->session;
        $this->message_type = $session->getFlash('message_type');
        $this->message = $session->getFlash('message');
    }

    public function run()
    {
        if($this->message_type && $this->message){
            return $this->render('alert',[
                'message' => $this->message,
                'message_type' => $this->message_type,
            ]);
        }else{
            return false;
        }
    }
}