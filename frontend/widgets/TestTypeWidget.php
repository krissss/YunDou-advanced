<?php

namespace frontend\widgets;


use yii\base\Widget;

class TestTypeWidget extends Widget{
    public $testLibrary;//必传参数，测试题目
    public $questionNumber = -1;//必传参数，当前题目开始的编号
    public $examFlag = false;  //是否是考试的标记

    private $testType = -1;//必需参数，当前题目的测试类型，例如：1、2、3、4

    public function init()
    {
        parent::init();
        $this->testType = $this->testLibrary['testTypeId'];
    }

    public function run()
    {
        return $this->render('testType_'.$this->testType,[
            'testLibrary' => $this->testLibrary,
            'questionNumber' => $this->questionNumber,
            'examFlag' => $this->examFlag
        ]);
    }

}