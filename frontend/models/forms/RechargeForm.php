<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;

class RechargeForm extends Model
{
    public $money;

    public function rules()
    {
        return [
            [['money'], 'required'],
            [['money'], 'integer','min'=>1,'max'=>100000],
        ];
    }

    public function attributeLabels()
    {
        return [
            'money' => '充值金额',
        ];
    }

    public function generateOrder(){
        require_once "./../functions/wxPayLibs/WxPay.Api.php";
        require_once "./../functions/wxPay/WxPay.JsApiPay.php";
        //①、获取用户openid
        $session = Yii::$app->session;
        $openId = $session->get('openId');
        $user = $session->get('user');
        if($user['weixin']!=$openId){
            echo ("用户标识不一致，无法完成订单");
            exit;
        }

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("test");
        $input->SetAttach("test");
        $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
        $input->SetTotal_fee("1");
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("test");
        $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        /** @var $order array */
        $order = WxPayApi::unifiedOrder($input);
        return $order;
    }
}