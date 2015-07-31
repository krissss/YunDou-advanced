<?php

namespace common\models;

use Yii;

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
     * 查询所有省份，返回json
     * @return string||json
     */
    public static function findAllForJson(){
        $provinces = Province::find()
            ->select(['provinceId as value','name as text'])
            ->asArray()
            ->all();
        return json_encode($provinces);
    }
}
