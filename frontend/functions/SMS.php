<?php
/**
 * 云片网发短信验证
 */

namespace frontend\functions;

class SMS
{
    const API_KEY = "fbdfc21ca9755fef969b02208b1507e9";

    /**
     * 通用接口发短信
     * @param $text //短信内容
     * @param $mobile  //接受短信的手机号
     * @return string
     */
    public static function send_sms($text, $mobile){
        $apiKey = SMS::API_KEY;
        $url="http://yunpian.com/v1/sms/send.json";
        $encoded_text = urlencode("$text");
        $post_string="apikey=$apiKey&text=$encoded_text&mobile=$mobile";
        return self::sock_post($url, $post_string);
    }

    /**
     * 模板接口发短信
     * @param $tpl_id //模板id
     * @param $tpl_value //模板值
     * @param $mobile //接受短信的手机号
     * @return string
     */
    public static function tpl_send_sms($tpl_id, $tpl_value, $mobile){
        $apiKey = SMS::API_KEY;
        $url="http://yunpian.com/v1/sms/tpl_send.json";
        $encoded_tpl_value = urlencode("$tpl_value");  //tpl_value需整体转义
        $post_string="apikey=$apiKey&tpl_id=$tpl_id&tpl_value=$encoded_tpl_value&mobile=$mobile";
        return self::sock_post($url, $post_string);
    }

    /**
     * url 为服务的url地址
     * query 为请求串
     */
    private static function sock_post($url,$query){
        $data = "";
        $info=parse_url($url);
        $fp=fsockopen($info["host"],80,$errno,$errstr,30);
        if(!$fp){
            return $data;
        }
        $head="POST ".$info['path']." HTTP/1.0\r\n";
        $head.="Host: ".$info['host']."\r\n";
        $head.="Referer: http://".$info['host'].$info['path']."\r\n";
        $head.="Content-type: application/x-www-form-urlencoded\r\n";
        $head.="Content-Length: ".strlen(trim($query))."\r\n";
        $head.="\r\n";
        $head.=trim($query);
        $write=fputs($fp,$head);
        $header = "";
        while ($str = trim(fgets($fp,4096))) {
            $header.=$str;
        }
        while (!feof($fp)) {
            $data .= fgets($fp,4096);
        }
        return $data;
    }
}