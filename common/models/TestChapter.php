<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "testchapter".
 *
 * @property integer $testChapterId
 * @property string $name
 * @property integer $majorJobId
 * @property string $preType
 * @property string $code
 * @property string $remark
 */
class TestChapter extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'testchapter';
    }

    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['majorJobId'], 'integer'],
            [['preType'], 'string', 'max' => 1],
            [['code'], 'string', 'max' => 4],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    public function attributeLabels()
    {
        return [
            'testChapterId' => 'Test Chapter ID',
            'name' => 'Name',
            'majorJobId' => 'Major Job ID',
            'preType' => 'Pre Type',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }

    public function getPreType(){
        return $this->hasOne(PreType::className(),['preTypeId'=>'preTypeId']);
    }

    public function getMajorJob(){
        return $this->hasOne(MajorJob::className(),['majorJobId'=>'majorJobId']);
    }
}
