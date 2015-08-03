<?php

namespace common\models;

use frontend\functions\DateFunctions;
use Yii;

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
}
