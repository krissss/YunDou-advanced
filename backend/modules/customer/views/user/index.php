<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '关联用户列表';
?>

<div class="widget flat">
    <div class="widget-body">
        <?= \common\widgets\AlertWidget::widget();?>
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['user/index'])?>">所有</a>
            <a class="btn btn-default" href="<?=Url::to(['user/search','type'=>'bitcoin-less','content'=>2000])?>">云豆余额小于2000</a>
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
                        <option value="bitcoin-less">云豆余额小于</option>
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
                <th class="text-align-center">操作</th>
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
                    <td>
                        <button class="distribute_bitcoin btn  btn-default " data-toggle="modal" data-target="#distribute_bitcoin" data-id="<?=$user->userId?>">
                            <span class="fa fa-leaf"></span>分配云豆
                        </button>
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
<div class="modal fade" id="distribute_bitcoin" tabindex="-1" role="dialog" aria-labelledby="分配云豆">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">分配云豆</h4>
            </div>
            <?=Html::beginForm(['user/distribute'], 'post', ['class' => 'form-horizontal']);?>
            <div class="modal-body">
                <input type="hidden" name="distribute_bitcoin_userId" value="">
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">接收云豆人</label>
                    <p class="col-sm-9 accept_nickname"></p>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 text-align-right">分配云豆数</label>
                    <input type="number" name="distribute_bitcoin_number" min="0" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn btn-primary">确定分配</button>
            </div>
            <?=Html::endForm();?>
        </div>
    </div>
</div>