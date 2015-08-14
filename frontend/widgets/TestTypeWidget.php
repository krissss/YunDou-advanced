<?php

namespace frontend\widgets;


use yii\base\Widget;

class TestTypeWidget extends Widget{
    public $testLibrary;    //必传参数，测试题目
    public $questionNumber = -1;    //必传参数，当前题目开始的编号

    public $examFlag = false;   //可传参数，判别是否是考试的

    private $testType = -1; //必需参数，当前题目的测试类型，例如：1、2、3、4
    private $collected = false; //必需参数，用户是否收藏该题

    public function init()
    {
        parent::init();
        $this->testType = $this->testLibrary['testTypeId'];
        if(!$this->examFlag){   //非考试才需要重点
            $collections = \Yii::$app->session->get('collections');
            if($collections){
                if(array_key_exists($this->testLibrary['testLibraryId'],$collections)){
                    $this->collected = true;
                }
            }
        }
    }

    public function run()
    {
        return $this->render('testType_'.$this->testType,[
            'testLibrary' => $this->testLibrary,
            'questionNumber' => $this->questionNumber,
            'examFlag' => $this->examFlag,
            'collected' => $this->collected
        ]);
    }

}