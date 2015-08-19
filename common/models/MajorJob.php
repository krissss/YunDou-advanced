<?php

namespace common\models;

use Yii;
use yii\caching\DbDependency;

/**
 * This is the model class for table "majorjob".
 *
 * @property integer $majorJobId
 * @property string $name
 * @property string $code
 * @property string $remark
 */
class MajorJob extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'majorjob';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 1],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'majorJobId' => 'Major Job ID',
            'name' => 'Name',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }

    /**
     * 查询专业岗位名称
     * @param $majorJobId
     * @return mixed
     * @throws \Exception
     */
    public static function findNameByMajorJobId($majorJobId){
        $result = Collection::getDb()->cache(function () use ($majorJobId) {
            return MajorJob::findOne($majorJobId);
        });
        return $result->name;
    }

    /**
     * 查询所有职业岗位，返回object
     * @return \common\models\MajorJob[]
     */
    public static function findAllForObject(){
        $dependency = new DbDependency([
            'sql'=> 'select count(*) from majorjob'
        ]);
        $result = Collection::getDb()->cache(function () {
            return MajorJob::find()->all();
        },null,$dependency);
        return $result;
    }
}
