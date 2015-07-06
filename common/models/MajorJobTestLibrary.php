<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "majorjobtestlibrary".
 *
 * @property integer $majorJobTestlibraryId
 * @property integer $majorJobId
 * @property integer $testLibraryId
 * @property string $remark
 */
class MajorJobTestLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'majorjobtestlibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['majorJobId', 'testLibraryId'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'majorJobTestlibraryId' => 'Major Job Testlibrary ID',
            'majorJobId' => 'Major Job ID',
            'testLibraryId' => 'Test Library ID',
            'remark' => 'Remark',
        ];
    }
}
