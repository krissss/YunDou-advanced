<?php

namespace common\models;

use common\functions\DateFunctions;
use Yii;
use yii\data\Sort;

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
 * @property string $signUpMajor
 * @property string $company
 * @property string $findPasswordQuestion
 * @property string $findPasswordAnswer
 * @property string $headImg
 * @property string $educationImg
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
            [['userId', 'replyUserId'], 'integer'],
            [['workTime', 'createDate', 'replyDate'], 'safe'],
            [['IDCard'], 'string', 'max' => 18],
            [['realName', 'findPasswordQuestion', 'findPasswordAnswer'], 'string', 'max' => 40],
            [['password', 'company'], 'string', 'max' => 30],
            [['cellphone'], 'string', 'max' => 11],
            [['education', 'technical'], 'string', 'max' => 10],
            [['major'], 'string', 'max' => 20],
            [['signUpMajor', 'headImg', 'educationImg'], 'string', 'max' => 50],
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
            'infoId' => 'Info ID',
            'userId' => 'User ID',
            'IDCard' => 'Idcard',
            'realName' => 'Real Name',
            'password' => 'Password',
            'cellphone' => 'Cellphone',
            'education' => 'Education',
            'major' => 'Major',
            'workTime' => 'Work Time',
            'technical' => 'Technical',
            'signUpMajor' => 'Sign Up Major',
            'company' => 'Company',
            'findPasswordQuestion' => 'Find Password Question',
            'findPasswordAnswer' => 'Find Password Answer',
            'headImg' => 'Head Img',
            'educationImg' => 'Education Img',
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
     * 检查用户是否有记录未处理或者已经有通过报名的记录
     * @param $userId
     * @return null|\common\models\Info
     */
    public static function checkUserRecordOrPass($userId){
        return Info::find()
            ->where(['userId'=>$userId])
            ->andWhere('state =\''.Info::STATE_RECORD.'\' or state = \''.Info::STATE_PASS.'\'')
            ->one();
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
     * 查询被拒绝的用户信息
     * @param $userId
     * @return array|null|\common\models\Info
     */
    public static function findRefusedByUserId($userId){
        return Info::find()
            ->where(['userId'=>$userId,'state'=>Info::STATE_REFUSE])
            ->one();
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
