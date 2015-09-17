<?php

namespace common\models;

use Yii;
use yii\caching\DbDependency;

/**
 * This is the model class for table "province".
 *
 * @property integer $provinceId
 * @property string $name
 * @property string $code
 * @property string $remark
 */
class Province extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'province';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 10],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'provinceId' => 'Province ID',
            'name' => 'Name',
            'code' => 'Code',
            'remark' => 'Remark',
        ];
    }

    /**
     * 查询省份名称
     * @param $provinceId
     * @return mixed
     * @throws \Exception
     */
    public static function findNameByProvinceId($provinceId){
        $result = Province::getDb()->cache(function () use ($provinceId) {
            return Province::findOne($provinceId);
        },3600);
        return $result->name;
    }

    /**
     * 查询所有省份，返回object
     * @return \common\models\Province[]
     */
    public static function findAllForObject(){
        $dependency = new DbDependency([
            'sql'=> 'select count(*) from province'
        ]);
        $result = Province::getDb()->cache(function () {
            return Province::find()->all();
        },3600,$dependency);
        return $result;
    }

}
