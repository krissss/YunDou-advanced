<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '关联用户列表';
$this->params['breadcrumbs'] = [
    $this->title
];
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['user/index'])?>">所有</a>
            <div id="search" class="collapse">
                <?= Html::beginForm(['user/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <hr>
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="userId">用户号</option>
                        <option value="nickname">用户昵称</option>
                        <option value="realname">真实姓名</option>
                        <option value="cellphone">手机号</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">用户号</th>
                <th class="text-align-center">昵称</th>
                <th class="text-align-center">真实姓名</th>
                <th class="text-align-center">手机号</th>
                <th class="text-align-center">性别</th>
                <th class="text-align-center">云豆数</th>
                <th class="text-align-center">推荐码</th>
                <th class="text-align-center">专业岗位</th>
                <th class="text-align-center">考试区域</th>
                <th class="text-align-center">工作单位</th>
                <th class="text-align-center">地址</th>
                <th class="text-align-center">实名认证时间</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user->userId ?></td>
                    <td class="nickname_<?=$user->userId?>"><?= $user->nickname ?></td>
                    <td><?= $user->realname ?></td>
                    <td><?= $user->cellphone ?></td>
                    <td><?= $user->sex ?></td>
                    <td><?= $user->bitcoin ?></td>
                    <td><?= $user->recommendCode ?></td>
                    <td><?= $user->majorJob['name'] ?></td>
                    <td><?= $user->province['name'] ?></td>
                    <td><?= $user->company ?></td>
                    <td><?= $user->address ?></td>
                    <td><?= $user->registerDate>0?$user->registerDate:'未实名认证' ?></td>
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