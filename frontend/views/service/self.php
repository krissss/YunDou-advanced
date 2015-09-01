<?php
/** 我的咨询页面 */
/** @var $models \common\models\Service[] */
/** @var $pages */

use yii\helpers\Url;
use common\models\Service;
?>
<div class="container-fluid">
    <div class="head-margin-20"></div>
    <p><strong>我的咨询与建议</strong></p>
    <div class="list-group no-margin-bottom">
        <?=count($models)==0?"无":""?>
        <?php foreach($models as $model):?>
            <a href="<?=Url::to(['service/view','id'=>$model['serviceId']])?>" class="list-group-item">
                <span class="badge"><?=$model['state']==Service::STATE_UNREPLY?"未回复":"已回复";?></span>
                <?=$model['content']?>
            </a>
        <?php endforeach;?>
    </div>
    <nav class="pull-right pagination_footer">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </nav>
    <div class="clearfix"></div>
</div>
