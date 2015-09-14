<?php

namespace frontend\functions;

use common\models\Service;
use common\models\Users;
use yii\base\Exception;
use Yii;
use yii\helpers\Url;

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
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    /**
     * 消息回复
     */
    public function responseMsg()
    {
        $cache = Yii::$app->cache;
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)) {
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            /** @var  $fromUsername String */
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $content = trim($postObj->Content) . "";  //转成字符串
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
            if ($event == "subscribe") {
                $response_msgType = "text";
                Users::wxSubscribe($fromUsername);
                $contentStr = "感谢您关注‘云豆讲堂’。\n\n在这里您可以‘免费模拟考试’、‘在线练习’、‘咨询考试事宜’，还可以请我们免费帮你代办考试报名。\n为了确保您获得准确的考试信息、试题库试题等，首先要进行‘实名认证’。\n实名认证：点击‘模拟与学习’》》‘实名认证’或者‘<a href='".Url::to(['account/register','openId'=>strval($fromUsername)],true)."'>点这里</a>.’都可以进行，云豆会保密你的信息哦！";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                echo $resultStr;
            }
            if ($event == "CLICK") {
                $eventKey = $postObj->EventKey;
                switch ($eventKey) {
                    case "online_practice":
                        $response_msgType = "text";
                        $contentStr = '<a href="'.Url::to(['practice/index','openId'=>strval($fromUsername)],true).'">点此在线练习</a>';
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                    case "simulate_exam":
                        $response_msgType = "text";
                        $contentStr = '<a href="'.Url::to(['exam/index','openId'=>strval($fromUsername)],true).'">点此模拟考试</a>';
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                    case "CLICK_REGISTER":  //实名认证
                        $response_msgType = "text";
                        $contentStr = "注册！";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                    case "CLICK_ZIXUN_REQUEST": //我要咨询
                        $cache->set("ZIXUN_REQUEST_" . $fromUsername, 'ZIXUN_REQUEST');
                        $response_msgType = "text";
                        $contentStr = "请输入您要咨询的内容\n输入#结束咨询";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                    case "CLICK_ZIXUN_VIEW": //查询咨询
                        self::ZIXUN_VIEW_Response($fromUsername, $toUsername);
                        break;
                    case "CLICK_BAOMING_REQUEST":   //我要报名
                        $response_msgType = "text";
                        $contentStr = "报名功能开发中。。。";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                    case "CLICK_YUNDOU":   //我的云豆
                        $response_msgType = "text";
                        $contentStr = "注册！";
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                        echo $resultStr;
                        break;
                }
            }
            //文本消息
            if ($msgType == 'text') {
                if (!empty($content)) {
                    if ($cache->get('ZIXUN_REQUEST_' . $fromUsername)) {
                        self::ZIXUN_REQUEST_Response($content, $fromUsername, $toUsername);
                    }
                    $response_msgType = "text";
                    $response_content = self::switchKeyword($content);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $response_content);
                    echo $resultStr;
                } else {
                    echo "individual";
                }
                exit;
            }
            //图片消息
            if ($msgType == 'image') {

            }


        } else {
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
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }

    private function switchKeyword($keyword)
    {
        switch ($keyword) {
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

    private function ZIXUN_REQUEST_Response($content, $fromUsername, $toUsername)
    {
        $cache = Yii::$app->cache;
        $type = "text";
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";
        if ($content == "#") {
            $cache->delete("ZIXUN_REQUEST_" . $fromUsername);
            $msg = "已经退出咨询";
        } else {
            Service::createServiceByOpenId($content,$fromUsername);
            $msg = "您的咨询已记录，请耐心等待回复";
        }
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), $type, $msg);
        echo $resultStr;
        exit;
    }

    public function ZIXUN_VIEW_Response($fromUsername, $toUsername)
    {
        $text = "<xml>
                <ToUserName><![CDATA[".$fromUsername."]]></ToUserName>
                <FromUserName><![CDATA[".$toUsername."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[news]]></MsgType>";
        $services = Service::findUserServiceByOpenId($fromUsername);
        $text.="<ArticleCount>".count($services)."</ArticleCount>";
        $text.="<Articles>";
        foreach($services as $service){
            $url = "www.baidu.com?serviceId=".$service->serviceId;
            $reply = $service->reply;
            if(!$reply){
                $reply = "暂时还没回复，请耐心等待";
            }
            $text.="<item>
                    <Title><![CDATA[".$service->content."]]></Title>
                    <Description><![CDATA[".$reply."]]></Description>
                    <PicUrl><![CDATA[picurl]]></PicUrl>
                    <Url><![CDATA[".$url."]]></Url>
                    </item>";
        }
        $text.="</Articles>
                </xml> ";
        echo $text;
        exit;
    }
}