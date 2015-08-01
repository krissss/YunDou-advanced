<?php

namespace frontend\functions;

use common\models\Users;
use yii\base\Exception;

class WeChatCallBack
{
    const TOKEN = "yundou";
    /**
     * 验证服务器
     * @throws Exception
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    /**
     * 消息回复
     */
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $content = trim($postObj->Content);
            $event = $postObj->Event;
            $msgType = $postObj->MsgType;
            $time = time();
            $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
            //订阅事件
            if($event == "subscribe"){
                $response_msgType = "text";
                Users::wxSubscribe($fromUsername);
                $contentStr = "感谢您的关注！";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                echo $resultStr;
            }
            if($event == "CLICK"){
                $eventKey = $postObj->EventKey;
                switch($eventKey){
                    case "CLICK_REGISTER":
                        $response_msgType = "text";
                        $contentStr = "注册！";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                }
            }
            //文本消息
            if($msgType == 'text'){
                if(!empty( $content ))
                {
                    $response_msgType = "text";
                    $response_content = self::switchKeyword($content);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $response_content);
                    echo $resultStr;
                }else{
                    echo "individual";
                }
                exit;
            }
            //图片消息
            if($msgType == 'image'){

            }


        }else {
            echo "";
            exit;
        }
    }

    private function checkSignature()
    {
        // you must define TOKEN by yourself
        if (!WeChatCallBack::TOKEN) {
            throw new Exception('TOKEN is not defined!');
        }

        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = WeChatCallBack::TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    private function switchKeyword($keyword){
        switch($keyword){
            case "你好":
                $msg = "你好";
                break;
            case "1":
                $msg = "1";
                break;
            default:
                $msg = $keyword;
        }
        return $msg;
    }
}