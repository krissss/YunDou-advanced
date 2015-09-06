<?php
/** 更新题库题目的表单 */
namespace backend\models\forms;

use common\functions\DateFunctions;
use common\models\TestLibrary;
use yii\base\Exception;
use yii\base\Model;

class UpdateTestLibraryForm extends Model
{
    public $testLibraryId;
    public $problem;
    public $question;
    public $options;
    public $answer;

    public function rules(){
        return [
            [['testLibraryId','question','options','answer'],'required'],
            [['testLibraryId'],'integer'],
            [['problem','question','options','answer'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'problem' => '题干',
            'question' => '问题',
            'options' => '选项',
            'answer' => '答案',
        ];
    }

    public function initWithId($testLibraryId){
        $testLibrary = TestLibrary::findOne($testLibraryId);
        $this->testLibraryId = $testLibraryId;
        if($testLibrary){
            $this->problem = $testLibrary->problem;
            $this->question = $testLibrary->question;
            $this->options = $testLibrary->options;
            $this->answer = $testLibrary->answer;
        }
    }

    public function update(){
        /** @var $testLibrary \common\models\TestLibrary */
        $testLibrary = TestLibrary::findOne($this->testLibraryId);
        $testLibrary->problem = $this->problem;
        $testLibrary->question = $this->question;
        $testLibrary->options = $this->options;
        $testLibrary->answer = $this->answer;
        $testLibrary->createDate = DateFunctions::getCurrentDate();
        $user = \Yii::$app->session->get('user');
        $testLibrary->createUserId = $user['userId'];
        if(!$testLibrary->update()){
            throw new Exception("UpdateTestLibraryForm update error");
        }
    }
}