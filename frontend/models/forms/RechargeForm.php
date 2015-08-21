<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

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
            'money' => '充值金额（元）',
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
        $input = new \WxPayUnifiedOrder();
        $input->SetBody("云豆充值");
        //$input->SetAttach("test");
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));
        Yii::$app->session->set('money',$this->money);
        //totalFee是以分为单位的，正式情况下应该乘以100
        $totalFee = $this->money;
        $input->SetTotal_fee($totalFee);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //$input->SetGoods_tag("test");
        $input->SetNotify_url(Url::base(true).'/notify.php');
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        /** @var $order array */
        $order = \WxPayApi::unifiedOrder($input);
        return $order;
    }
}