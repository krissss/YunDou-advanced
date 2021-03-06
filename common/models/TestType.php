<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "testtype".
 *
 * @property integer $testTypeId
 * @property string $name
 * @property string $code
 * @property string $remark
 */
class TestType extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'testtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 3],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'testTypeId' => 'Test Type ID',
            'name' => 'Name',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }

    public static function findAllForObject(){
        return TestType::find()->all();
    }

    /**
     * 查询测试类型名称，缓存24小时
     * @param $testTypeId
     * @return mixed
     * @throws \Exception
     */
    public static function findNameById($testTypeId){
        $result = TestType::getDb()->cache(function() use ($testTypeId){
            return TestType::findOne($testTypeId);
        },24*3600);
        return $result->name;
    }
}
