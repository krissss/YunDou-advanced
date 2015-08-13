<?php

//\frontend\assets\WeChatJsAsset::register($this);
use frontend\functions\WeiXinFunctions;
use yii\helpers\Url;
$this->registerJsFile('frontend/web/js/yundou-wechat.js',['depends'=>['frontend\assets\AppAsset']]);

$timestamp = time();
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: '<?=WeiXinFunctions::getAppId()?>', // 必填，公众号的唯一标识
        timestamp: '<?=$timestamp?>', // 必填，生成签名的时间戳
        nonceStr: 'yundou-js', // 必填，生成签名的随机串
        //jsticket: '<?//=WeiXinFunctions::getJsApiTicket()?>',
        //url: '<?//=Url::current([],true)?>',
        signature: '<?=WeiXinFunctions::generateJsSignature(Url::current([],true),$timestamp)?>',// 必填，签名，见附录1
        jsApiList: [
            'checkJsApi',
            'onMenuShareTimeline',    //分享到朋友圈
        ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    });
    alert('<?=Url::current([],true)?>');
    wx.error(function(res){
        console.log(res);
    });
</script>

<div class="alert alert-info" role="alert">
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
    <p>推荐说明</p>
</div>
<div class="container-fluid">
    <div class="text-center">
        <span class="recommend_code"><strong>我的推荐码:</strong>1438857749W6a1p</span>
        <button class="btn btn-primary margin-left-10" data-code="1438857749W6a1p">复制</button>
    </div>
    <div class="text-center">
        <button class="btn btn-primary share">分享</button>
    </div>
</div>
