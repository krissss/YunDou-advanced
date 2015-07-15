<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $userId
 * @property string $username
 * @property string $email
 * @property string $cellphone
 * @property string $weixin
 * @property integer $majorJobId
 * @property string $nickname
 * @property string $realname
 * @property string $introduce
 * @property integer $bitcoin
 * @property integer $province
 * @property integer $city
 * @property string $company
 * @property string $address
 * @property string $registerDate
 * @property integer $role
 * @property integer $recommendUserID
 * @property string $remark
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorJobId', 'bitcoin', 'province', 'city', 'role', 'recommendUserID'], 'integer'],
            [['registerDate'], 'safe'],
            [['username', 'email', 'weixin', 'nickname', 'realname', 'company', 'address'], 'string', 'max' => 50],
            [['cellphone'], 'string', 'max' => 11],
            [['introduce', 'remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'userId' => 'User ID',
            'username' => 'Username',
            'email' => 'Email',
            'cellphone' => 'Cellphone',
            'weixin' => 'Weixin',
            'majorJobId' => 'Major Job ID',
            'nickname' => 'Nickname',
            'realname' => 'Realname',
            'introduce' => 'Introduce',
            'bitcoin' => 'Bitcoin',
            'province' => 'Province',
            'city' => 'City',
            'company' => 'Company',
            'address' => 'Address',
            'registerDate' => 'Register Date',
            'role' => 'Role',
            'recommendUserID' => 'Recommend User ID',
            'remark' => 'Remark',
        ];
    }
}
