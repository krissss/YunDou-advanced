<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '系统用户管理';
?>

<div class="widget flat">
    <div class="widget-body">
        <!--<div class="well bordered-left bordered-blue">
            <a class="btn btn-default add_user_aaa" href="javascript:void(0);"><i class="fa fa-search"></i>添加伙伴</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询用户</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?/*=Url::to(['user-aaa/index'])*/?>">所有</a>
            <div id="search" class="collapse">
                <hr>
                <?/*= Html::beginForm(['user-aaa/search'], 'post', ['class' => 'form-inline']) */?>
                <div class="form-group">
                    <label>搜索：</label>
                    <select class="form-control" name="type">
                        <option value="nickname">用户名称</option>
                        <option value="cellphone">手机号</option>
                        <option value="address">地址</option>
                        <option value="recommendCode">推荐码</option>
                    </select>
                    <input type="text" name="content" class="form-control" placeholder="请输入查找内容">
                    <button type="submit" class="btn  btn-small btn btn-primary">查找</button>
                </div>
                <?/*= Html::endForm();*/?>
            </div>
        </div>-->
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">登录名</th>
                <th class="text-align-center">用户名称</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user->userId ?></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->nickname ?></td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
        <div class="clearfix"></div>
    </div>
</div>
