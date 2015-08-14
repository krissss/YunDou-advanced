<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "service".
 *
 * @property integer $serviceId
 * @property integer $userId
 * @property string $content
 * @property string $state
 * @property string $reply
 * @property integer $replyUserId
 * @property string $createDate
 * @property string $replyDate
 * @property string $remark
 */
class Service extends \yii\db\ActiveRecord
{
    const STATE_UNREPLY = "A";
    const STATE_REPLIED = "B";
    const STATE_PUBLISH = "Z";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['userId', 'replyUserId'], 'integer'],
            [['content', 'reply'], 'string', 'max' => 200],
            [['state'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100],
            [['createDate','replyDate'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'serviceId' => 'Service ID',
            'userId' => 'User ID',
            'content' => 'Content',
            'reply' => 'Reply',
            'replyUserId' => 'Reply User ID',
            'createDate' => 'Create Date',
            'replyDate' => 'Reply Date',
            'remark' => 'Remark',
        ];
    }

    public function getCreateUser(){
        return $this->hasOne(Users::className(),['userId'=>'userId']);
    }

    public function getReplyUser(){
        return $this->hasOne(Users::className(),['userId'=>'replyUserId']);
    }

    public function getStateName(){
        if($this->state == Service::STATE_UNREPLY){
            return "未回复";
        }elseif($this->state == Service::STATE_REPLIED){
            return "已回复";
        }elseif($this->state == Service::STATE_PUBLISH){
            return "已发布";
        }else{
            return "未知状态";
        }
    }

    public static function createServiceByOpenId($content,$openId){
        $service = new Service();
        $user = Users::findByWeiXin($openId);
        $service->userId = $user->userId;
        $service->content = $content;
        $service->createDate = DateFunctions::getCurrentDate();
        $service->state = Service::STATE_UNREPLY;
        $service->save();
    }

    public static function findUserServiceByOpenId($openId,$limit=5){
        $user = Users::findByWeiXin($openId);
        return Service::find()
            ->where(['userId'=>$user->userId])
            ->limit($limit)
            ->all();
    }

    public static function replyService($serviceId,$reply,$publish=false){
        $user = Yii::$app->session->get('user');
        if(!$user){
            throw new Exception("Service replyService session user not exist!");
        }
        $service = Service::findOne($serviceId);
        if($publish){
            $service->state = Service::STATE_PUBLISH;
        }else{
            $service->state = Service::STATE_REPLIED;
        }
        $service->reply = $reply;
        $service->replyUserId = $user['userId'];
        $service->replyDate = DateFunctions::getCurrentDate();
        if(!$service->update()){
            throw new Exception("Service replyService update error!");
        }
    }

    public static function changePublish($serviceId){
        $service = Service::findOne($serviceId);
        if($service->state == Service::STATE_REPLIED){
            $service->state = Service::STATE_PUBLISH;
            $msg = 'publish';
        }elseif($service->state == Service::STATE_PUBLISH){
            $service->state = Service::STATE_REPLIED;
            $msg = 'replied';
        }else{
            $msg = 'error';
        }
        if(!$service->update()){
            throw new Exception("Service replyService update error!");
        }
        return $msg;
    }

    public static function findAllByUser($userId){
        return Service::find()
            ->where(['userId'=>$userId])
            ->orderBy(['createDate'=>SORT_DESC])
            ->all();
    }

    public static function findPublished(){
        return Service::find()
            ->where(['state'=>Service::STATE_PUBLISH])
            ->orderBy(['replyDate'=>SORT_DESC])
            ->all();
    }
}
