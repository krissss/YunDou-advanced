<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setpapertestlibrary".
 *
 * @property integer $setPaperTestLibraryId
 * @property integer $setPaperId
 * @property integer $testLibraryId
 * @property string $remark
 */
class SetPaperTestLibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'setpapertestlibrary';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['setPaperId', 'testLibraryId'], 'integer'],
            [['remark'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'setPaperTestLibraryId' => 'Set Paper Test Library ID',
            'setPaperId' => 'Set Paper ID',
            'testLibraryId' => 'Test Library ID',
            'remark' => 'Remark',
        ];
    }
}
