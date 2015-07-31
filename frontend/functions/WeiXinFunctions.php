<?php

namespace frontend\functions;

use Yii;

class WeiXinFunctions
{
    //保存类实例的静态成员变量
    private static $_instance;

    private $appId = "wxcf0cd66d7cdf0708";
    private $appSecret = "8bcd5a776d588ff7dc2f66c10b7efd11";

    //private标记的构造方法
    private function __construct(){}

    //创建__clone方法防止对象被复制克隆
    public function __clone(){}

    //单例方法,用于访问实例的公共的静态方法
    public static function getInstance(){
        if(!(self::$_instance instanceof self)){
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    /**
     * 将access_token存入缓存
     * @param $access_token
     * @param $expires_in
     */
    public function setResult($access_token,$expires_in){
        $cache = Yii::$app->cache;
        $cache->set('access_token',$access_token,$expires_in-10);   //缓存时间比预计时间少10秒，确保access_token有效
    }

    /**
     * 获取access_token
     * @return mixed
     */
    public function getAccessToken_in(){
        $access_token = Yii::$app->cache->get('access_token');
        if(!$access_token){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
            $fp=file_get_contents($url) or die("can not open $url");
            $result = json_decode($fp);
            $this->setResult($result->access_token,$result->expires_in);
        }
        return Yii::$app->cache->get('access_token');
    }

    /**
     * 获取用户信息
     * @param $openId
     * @return mixed
     */
    public function getUserInfo_in($openId){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken_in().'&openid='.$openId;
        $fp=file_get_contents($url) or die("can not open $url");
        $result = json_decode($fp);
        return $result;
    }

    /**
     * 外部直接调用的获取access_token方法
     * @return mixed
     */
    public static function getAccessToken(){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getAccessToken_in();
    }

    public static function getUserInfo($openId){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getUserInfo_in($openId);
    }


}