<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */
/* @var $searchModel backend\models\UsersSearch */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', '发票管理');
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <hr>
            <div class="view">
                <label>快速查找:</label>
                <a class="btn btn-default" href="<?=Url::to(['invoice/index'])?>">所有</a>
                <a class="btn btn-default" href="<?=Url::to(['pay/search','type'=>'role','content'=>'AAA'])?>">3A级用户</a>
                <!--                <a class="btn btn-default" href="javascript:void(0);">7天未登录</a>-->
            </div>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['invoice/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="role">用户类型</option>
                        <option value="username">用户名称</option>
                        <option value="money-more">开票金额大于</option>
                        <option value="money-equal">开票金额等于</option>
                        <option value="money-less">开票金额小于</option>
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
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">用户类型</th>
                <th class="text-align-center">申请开票金额</th>
                <th class="text-align-center">申请开票时间</th>
                <th class="text-align-center">详细地址</th>
                <th class="text-align-center">是否开票</th>
                <!--                <th class="text-align-center">云豆余额</th>-->
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($models as $model):?>
                <tr>
                    <td><?= $model->invoiceId ?></td>
                    <td><?= $model->userId ?></td>
                    <td><?= $model->users['username'] ?></td>
                    <td><?= $model->users['roleName'] ?></td>
<!--                    <td>-->
<!--                        --><?php //if($model->type):?>
<!--                            <span class="fa fa-minus"></span>-->
<!--                        --><?php //else:?>
<!--                            <span class="fa fa-plus    "></span>-->
<!--                        --><?php //endif;?>
<!--                        --><?//= $model->bitcoin ?>
<!--                    </td>-->
                    <td><?= $model->money ?></td>
                    <td><?= $model->createDate ?></td>
                    <td><?= $model->address ?></td>
                    <td><?= $model->description ?></td>
                    <td>
                        <a href="<?= Url::to(['invoiceId/update', 'invoiceId' => $model->invoiceId]) ?>"><span class="glyphicon glyphicon-pencil">  </span></a>
                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'invoiceId' => $model->invoiceId], [
                            'data' => [
                                'confirm' => '你确定要删除这条信息记录吗？',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </td>
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
