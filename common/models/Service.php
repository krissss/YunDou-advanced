<?php

namespace common\models;

use frontend\functions\DateFunctions;
use Yii;
use yii\base\Exception;

/**
 * This is the model class for table "service".
 *
 * @property integer $serviceId
 * @property integer $userId
 * @property string $content
 * @property string $reply
 * @property integer $replyUserId
 * @property string $createDate
 * @property string $replyDate
 * @property string $remark
 */
class Service extends \yii\db\ActiveRecord
{
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

    public static function createServiceByOpenId($content,$openId){
        $service = new Service();
        $user = Users::findByWeiXin($openId);
        $service->userId = $user->userId;
        $service->content = $content;
        $service->createDate = DateFunctions::getCurrentDate();
        $service->save();
    }

    public static function findUserServiceByOpenId($openId,$limit=5){
        $user = Users::findByWeiXin($openId);
        return Service::find()
            ->where(['userId'=>$user->userId])
            ->limit($limit)
            ->all();
    }

    public static function replyService($serviceId,$reply){
        $user = Yii::$app->session->get('user');
        if(!$user){
            throw new Exception("Service replyService session user not exist!");
        }
        $service = Service::findOne($serviceId);
        $service->reply = $reply;
        $service->replyUserId = $user['userId'];
        $service->replyDate = DateFunctions::getCurrentDate();
        if(!$service->update()){
            throw new Exception("Service replyService update error!");
        }
    }
}
