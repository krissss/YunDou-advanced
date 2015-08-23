<?php
/** @var $user \common\models\Users */

use yii\helpers\Url;
?>
<div class="container">
    <div class="panel">
        <div class="panel-body">
            <div class="media">
                <div class="media-left media-middle">
                    <a href="#">
                        <img class="media-object" src="<?=Url::base(true)?>/images/logo2.png" alt="云豆讲堂" title="云豆讲堂" style="height: 50px">
                    </a>
                </div>
                <div class="media-body">
                    <p>关注‘云豆讲堂’的官方微信，平台将为您推送最新考试资讯与建筑行业权威信息，
                        同时还提供在线模拟考试、在线练习、在线听课服务，帮助您顺利完成考试，取得好成绩！
                        <br>实名认证时填写我的推荐码，你就可以享受5%的云豆返点哦！</p>
                </div>
            </div>
        </div>
        <div class="alert alert-info" role="alert">
            <p>我通过云豆讲堂进行在线学习和模拟考试，系统很操作很方便、题库也很不错，大家一起来学习吧。</p>
            <p class="text-center"><strong><?=$user['nickname']?>的推荐码：<?=$user['recommendCode']?></strong></p>
        </div>
        <div class="panel-body">
            <p><strong>云豆讲堂——专业的在线职业培训平台</strong></p>
            <p>权威的试题、多种学习模式，做题与娱乐两不误，动力十足。随时随地，想学就学，从此高分不是梦。</p>
        </div>
    </div>
</div>