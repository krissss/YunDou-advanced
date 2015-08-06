<?php

namespace backend\models\forms;

use yii\base\Model;

class UploadForm extends Model
{
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file'],
        ];
    }
}