<?php

namespace frontend\models\forms;

use common\functions\DateFunctions;
use Yii;
use common\models\Service;
use yii\base\Exception;
use yii\base\Model;

class ConsultForm extends Model
{
    public $content;

    public function rules()
    {
        return [
            [['content'], 'required'],
            [['content'], 'string','min'=>10,'max'=>200],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => '咨询或建议内容',
        ];
    }

    public function record(){
        $user = Yii::$app->session->get('user');
        $service = new Service();
        $service->userId = $user['userId'];
        $service->content = $this->content;
        $service->state = Service::STATE_UNREPLY;
        $service->createDate = DateFunctions::getCurrentDate();
        if(!$service->save()){
            throw new Exception("ConsultForm save error");
        }
    }
}