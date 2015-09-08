<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'A级用户列表';
$this->params['breadcrumbs'] = [
    '用户管理',
    $this->title
];
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询用户</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['user-a/index'])?>">所有</a>
            <!--<a class="btn btn-default" href="javascript:void(0);">一周新用户</a>
            <a class="btn btn-default" href="javascript:void(0);">一周登陆用户</a>
            <a class="btn btn-default" href="javascript:void(0);">四周未登陆</a>-->
            <a class="btn btn-default" href="<?=Url::to(['user-a/search','type'=>'province','content'=>'江苏'])?>">江苏</a>
            <a class="btn btn-default" href="<?=Url::to(['user-a/search','type'=>'province','content'=>'安徽 '])?>">安徽</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['user-a/search'], 'post', ['class' => 'form-inline']) ?>
                    <div class="form-group">
                        <label>搜索：</label>
                        <select class="form-control" name="type">
                            <option value="nickname">用户昵称</option>
                            <option value="cellphone">手机号</option>
                            <option value="province">考试区域</option>
                            <option value="majorJob">专业岗位类型</option>
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
                    <th class="text-align-center">微信id</th>
                    <th class="text-align-center">昵称</th>
                    <th class="text-align-center">手机号</th>
                    <th class="text-align-center">性别</th>
                    <th class="text-align-center">推荐码</th>
                    <th class="text-align-center">推荐用户</th>
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
                    <td><?= $user->weixin ?></td>
                    <td><?= $user->nickname ?></td>
                    <td><?= $user->cellphone ?></td>
                    <td><?= $user->sex ?></td>
                    <td><?= $user->recommendCode ?></td>
                    <td><?= $user->recommendUser['nickname'] ?></td>
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
