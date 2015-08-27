<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;

/**
 * This is the model class for table "info".
 *
 * @property integer $infoId
 * @property integer $userId
 * @property string $IDCard
 * @property string $realName
 * @property string $password
 * @property string $cellphone
 * @property string $education
 * @property string $major
 * @property string $workTime
 * @property string $technical
 * @property string $company
 * @property string $findPasswordQuestion
 * @property string $findPasswordAnswer
 * @property string $headImg
 * @property string $IDCardImg1
 * @property string $IDCardImg2
 * @property string $state
 * @property string $replyContent
 * @property string $createDate
 * @property integer $replyUserId
 * @property string $replyDate
 * @property string $remark
 */
class Info extends \yii\db\ActiveRecord
{
    const STATE_RECORD = 'F';
    const STATE_PASS = 'T';
    const STATE_REFUSE = 'R';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'info';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['workTime', 'createDate', 'replyDate'], 'safe'],
            [['replyUserId'], 'integer'],
            [['IDCard'], 'string', 'max' => 18],
            [['realName', 'findPasswordQuestion', 'findPasswordAnswer'], 'string', 'max' => 40],
            [['password', 'company'], 'string', 'max' => 30],
            [['cellphone'], 'string', 'max' => 11],
            [['education', 'technical'], 'string', 'max' => 10],
            [['major'], 'string', 'max' => 20],
            [['headImg', 'IDCardImg1', 'IDCardImg2'], 'string', 'max' => 50],
            [['state'], 'string', 'max' => 1],
            [['replyContent', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'infoId' => 'info ID',
            'userId' => 'User ID',
            'IDCard' => 'Idcard',
            'realName' => 'Real Name',
            'password' => 'Password',
            'cellphone' => 'Cellphone',
            'education' => 'Education',
            'major' => 'Major',
            'workTime' => 'Work Time',
            'technical' => 'Technical',
            'company' => 'Company',
            'findPasswordQuestion' => 'Find Password Question',
            'findPasswordAnswer' => 'Find Password Answer',
            'headImg' => 'Head Img',
            'IDCardImg1' => 'Idcard Img1',
            'IDCardImg2' => 'Idcard Img2',
            'state' => 'State',
            'replyContent' => 'Reply Content',
            'createDate' => 'Create Date',
            'replyUserId' => 'Reply User ID',
            'replyDate' => 'Reply Date',
            'remark' => 'Remark',
        ];
    }

    public function getStateName(){
        if($this->state == Info::STATE_RECORD){
            return "待填报";
        }elseif($this->state == Info::STATE_PASS){
            return "已填报";
        }elseif($this->state == Info::STATE_REFUSE){
            return "填报失败";
        }else{
            return "未知";
        }
    }

    /**
     * 根据用户查找
     * @param $userId
     * @return null|\common\models\Info
     */
    public static function findByUserId($userId){
        return Info::findOne(['userId'=>$userId]);
    }

    /**
     * 根据身份证查找
     * @param $IDCard
     * @return null|\common\models\Info
     */
    public static function findByIDCard($IDCard){
        return Info::findOne(['IDCard'=>$IDCard]);
    }

    /**
     * 修改状态，有错误会返回错误
     * @param $infoId
     * @param $state
     * @return bool|string
     */
    public static function changeState($infoId,$state,$replyContent=null){
        $info = Info::findOne($infoId);
        if($info){
            $info->state = $state;
            $user = Yii::$app->session->get('user');
            $info->replyUserId = $user['userId'];
            $info->replyDate = DateFunctions::getCurrentDate();
            $info->replyContent = $replyContent;
            $info->save();
            return false;
        }else{
            return "不存在该信息";
        }
    }
}
