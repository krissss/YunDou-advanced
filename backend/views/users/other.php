<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */
/* @var $searchModel backend\models\UsersSearch */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '微信用户管理';
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="<?=Url::to(['users/create'])?>"><i class="fa fa-plus"></i>添加用户</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询用户</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['users/index'])?>">所有</a>
            <a class="btn btn-default" href="javascript:void(0);">一周新用户</a>
            <a class="btn btn-default" href="javascript:void(0);">一周登陆用户</a>
            <a class="btn btn-default" href="javascript:void(0);">四周未登陆</a>
            <a class="btn btn-default" href="javascript:void(0);">A级</a>
            <a class="btn btn-default" href="javascript:void(0);">AA级</a>
            <a class="btn btn-default" href="javascript:void(0);">AAA级</a>
            <a class="btn btn-default" href="javascript:void(0);">云豆大于5000</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['users/search'], 'post', ['class' => 'form-inline']) ?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="name">客户名</option>
                        <option value="cellphone">手机号</option>
                        <option value="city">城市</option>
                        <option value="role">客户级别</option>
                        <option value="bitcoin">云豆数</option>
                        <option value="majorJob">云豆数</option>
                        <option value="company">单位</option>
                        <option value="registerDate">注册时间</option>
                        <option value="province">考试区域</option>
                        <option value="weixin">微信号</option>
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
                <th class="text-align-center">工作单位</th>
                <th class="text-align-center">用户等级</th>
                <th class="text-align-center">推荐码</th>
                <th class="text-align-center">绑定码</th>
                <th class="text-align-center">手机</th>
                <th class="text-align-center">地址</th>
                <th class="text-align-center">云豆余额</th>
                <th class="text-align-center">已经提现</th>
                <th class="text-align-center">编辑</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><a href="<?= Url::to(['users/view', 'userId' => $user->userId]) ?>"><?= $user->weixin ?></a></td>
                    <td><?= $user->nickname ?></td>
                    <td><?= $user->company ?></td>
                    <td><?= $user->roleName ?></td>
                    <td><?= $user->roleName ?></td>
                    <td><?= $user->roleName ?></td>
                    <td><?= $user->cellphone ?></td>
                    <td><?= $user->address ?></td>
                    <td><?= $user->bitcoin ?></td>
                    <td><?= $user->roleName ?></td>
                    <td>
                    <a href="<?= Url::to(['users/update', 'userId' => $user->userId]) ?>"><span class="glyphicon glyphicon-pencil">  </span></a>
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
