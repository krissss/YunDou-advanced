<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property integer $serviceId
 * @property integer $userId
 * @property string $content
 * @property string $reply
 * @property integer $replyUserId
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
            [['remark'], 'string', 'max' => 100]
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
            'remark' => 'Remark',
        ];
    }
}
