<?php

namespace frontend\functions;

use Yii;

class WeiXinFunctions
{
    //保存类实例的静态成员变量
    private static $_instance;

    private $appId = "wxcf0cd66d7cdf0708";
    private $appSecret = "8bcd5a776d588ff7dc2f66c10b7efd11";

    private $site_url = "selab.sju.edu.cn/yundou";

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
     * 获取全局access_token
     * @return mixed
     */
    public function getAccessToken_in(){
        $cache = Yii::$app->cache;
        $access_token = $cache->get('access_token');
        if(!$access_token){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
            $fp=file_get_contents($url) or die("can not open $url");
            $result = json_decode($fp);
            $cache->set('access_token',$result->access_token,$result->expires_in-10);   //缓存时间比预计时间少10秒，确保access_token有效
        }
        return $cache->get('access_token');
    }

    /**
     * 获取网页授权access_token
     * @param $code
     * @return mixed
     */
    public function getAuthorAccessToken_in($code){
        /*$cache = Yii::$app->cache;
        $access_token = $cache->get('author_access_token');
        if(!$access_token){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appId.'&secret='.$this->appSecret;
            $fp=file_get_contents($url) or die("can not open $url");
            $result = json_decode($fp);
            $cache->set('access_token',$result->access_token,$result->expires_in-10);   //缓存时间比预计时间少10秒，确保access_token有效
        }
        return Yii::$app->cache->get('access_token');
        $url = "https://api.weixin.qq.com/sns/auth?access_token=ACCESS_TOKEN&openid=OPENID";
        if(){

        }*/
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appId.'&secret='.$this->appSecret.'&code='.$code.'&grant_type=authorization_code';
        $fp=file_get_contents($url) or die("can not open $url");
        return json_decode($fp);
    }

    /**
     * 获取用户信息，分为两种情况：
     * $accessToken=null时(即不传该值)，使用全局access_token；传入$accessToken时(即网页授权)，使用网页授权获得的access_token
     * @param $accessToken
     * @param $openId
     * @return mixed
     */
    public function getUserInfo_in($openId, $accessToken=null){
        if(!$accessToken){
            $accessToken = $this->getAccessToken_in();
        }
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openId;
        $fp=file_get_contents($url) or die("can not open $url");
        return json_decode($fp);
    }

    /**
     * 创建菜单
     * @return mixed
     */
    public function createMenu_in(){
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken_in();
        $data = [
          "button"=>[
              [
                  "name"=>"模拟与学习",
                  "sub_button"=>[
                      [
                          "type"=>"click",
                          "name"=>"模拟考试",
                          "key"=>"simulate_exam",
                      ],[
                          "type"=>"click",
                          "name"=>"在线练习",
                          "key"=>"online_practice",
                      ]
                  ]
              ],[
                  "name"=>"咨询与报名",
                  "sub_button"=>[
                      [
                          "type"=>"click",
                          "name"=>"我要咨询",
                          "key"=>"CLICK_ZIXUN_REQUEST",
                      ],[
                          "type"=>"click",
                          "name"=>"查询咨询",
                          "key"=>"CLICK_ZIXUN_VIEW",
                      ],[
                          "type"=>"click",
                          "name"=>"我要报名",
                          "key"=>"CLICK_BAOMING_REQUEST",
                      ]
                  ]
              ],[
                  "name"=>"我的云豆",
                  "sub_button"=>[
                      [
                          "type"=>"click",
                          "name"=>"我的云豆",
                          "key"=>"CLICK_YUNDOU",
                      ],[
                          "type"=>"view",
                          "name"=>"实名认证2",
                          "url"=>"http://".$this->site_url."/frontend/web/?r=account/register",
                      ],[
                          "type"=>"view",
                          "name"=>"实名认证2",
                          "url"=>"http://".$this->site_url."/frontend/web/?r=account/register",
                      ]
                  ]
              ]
          ]
        ];
        $data = json_encode($data,JSON_UNESCAPED_UNICODE);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($curl);
        if($error=curl_error($curl)){
            die($error);
        }
        curl_close($curl);
        return $response;
    }

    public function getAuthorizeUrl_in($redirect_uri){
        $scope = "snsapi_base";
        $state = "YUN";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".$redirect_uri.
            "&response_type=code&scope=".$scope."&state=".$state."#wechat_redirect";
        return $url;
    }

    /**
     * 外部直接调用的获取access_token方法
     * @return mixed
     */
    public static function getAccessToken(){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getAccessToken_in();
    }

    public static function getAuthAccessToken($code){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getAuthorAccessToken_in($code);
    }

    public static function getUserInfo($openId,$accessToken=null){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getUserInfo_in($openId,$accessToken);
    }

    public static function createMenu(){
        $wx = WeiXinFunctions::getInstance();
        return $wx->createMenu_in();
    }

    public static function getAuthorizeUrl($redirect_uri){
        $wx = WeiXinFunctions::getInstance();
        return $wx->getAuthorizeUrl_in($redirect_uri);
    }


}