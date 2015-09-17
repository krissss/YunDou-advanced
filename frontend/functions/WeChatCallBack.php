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
                $contentStr = "感谢您关注云豆讲堂。\n\n在这里，您不但可以进行免费模拟考试、免费咨询考试信息、免费代办报名，还可以在线考试练习、与高手们交流学习心得、与朋友们整合考试资源。\n\n为了确保您获得准确的考试信息、试题库信息等，请您首先进行‘实名认证’。相关信息我们会保密哦！\n\n实名认证步骤：点击'模拟与学习'>>'实名认证'或者'<a href='".Url::to(['account/register','openId'=>strval($fromUsername)],true)."'>点击这里</a>'都可以进行，祝您考试顺利！\n\n<a href='http://x.eqxiu.com/s/d3HPgVVe?eqrcode=1&from=groupmessage&isappinstalled=0'>查看云豆平台应用手册</a>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $response_msgType, $contentStr);
                echo $resultStr;
                exit;
            }
            //任何消息，转往多客服
            if (!empty($content)) {
                $serviceTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                                </xml>";
                $resultStr = sprintf($serviceTpl, $fromUsername, $toUsername, $time);
                echo $resultStr;
                exit;
            } else {
                echo "individual";
                exit;
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