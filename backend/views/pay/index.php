<?php
/* @var $this yii\web\View */
/* @var $models common\models\Pay[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title =  '资金管理';
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['pay/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['pay/search','type'=>'money-more','content'=>'500'])?>">充值金额大于500用户</a>
            <a class="btn btn-default" href="<?=Url::to(['pay/search','type'=>'role','content'=>'A'])?>">A级</a>
            <a class="btn btn-default" href="<?=Url::to(['pay/search','type'=>'role','content'=>'AA'])?>">AA级</a>
            <a class="btn btn-default" href="<?=Url::to(['pay/search','type'=>'role','content'=>'AAA'])?>">AAA级</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['pay/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="username">用户名称</option>
                        <option value="money-more">充值金额大于</option>
                        <option value="money-equal">充值金额为</option>
                        <option value="money-less">充值金额小于</option>
                        <option value="bitcoin-more">获得云豆数大于</option>
                        <option value="bitcoin-equal">获得云豆数为</option>
                        <option value="bitcoin-less">获得云豆数小于</option>

                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr> <th class="text-align-center">序号</th>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">用户昵称</th>
                <th class="text-align-center">用户类型</th>
                <th class="text-align-center">充值金额</th>
                <th class="text-align-center">获得云豆数</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->payId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['nickname'] ?></td>
                    <td><?= $model->users['roleName'] ?></td>
                    <td><?= $model->money ?></td>
                    <td><?= $model->money ?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <nav class="pull-right pagination_footer">
            <?php echo \yii\widgets\LinkPager::widget([
                'pagination' => $pages,
            ]);?>
        </nav>
        <div class="clearfix"></div>
    </div>
</div>
