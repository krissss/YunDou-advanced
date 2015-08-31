<?php

namespace common\models;

use Yii;
use yii\caching\DbDependency;

/**
 * This is the model class for table "department".
 *
 * @property integer $departmentId
 * @property string $name
 * @property string $remark
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 50],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'departmentId' => 'Department ID',
            'name' => 'Name',
            'remark' => 'Remark',
        ];
    }

    /**
     * 查询所有职业岗位，返回object
     * @return \common\models\Department[]
     */
    public static function findAllForObject(){
        $dependency = new DbDependency([
            'sql'=> 'select count(*) from department'
        ]);
        $result = Department::getDb()->cache(function () {
            return Department::find()->all();
        },null,$dependency);
        return $result;
    }
}
