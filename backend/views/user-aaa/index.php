<?php
/* @var $this yii\web\View */
/* @var $users common\models\Users[] */
/* @var $pages */

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Users;
use common\models\Money;
use common\models\IncomeConsume;

$this->title = 'AAA级伙伴管理';
?>

<div class="widget flat">
    <div class="widget-body">
        <div class="well bordered-left bordered-blue">
            <a class="btn btn-default add_user_aaa" href="javascript:void(0);"><i class="fa fa-search"></i>添加伙伴</a>
            <a class="btn btn-default" href="javascript:void(0);" data-toggle="collapse" data-target="#search"><i class="fa fa-search"></i>查询用户</a>
            <label>快速查找:</label>
            <a class="btn btn-default" href="<?=Url::to(['user-aaa/index'])?>">所有</a>
            <div id="search" class="collapse">
                <hr>
                <?= Html::beginForm(['user-aaa/search'], 'post', ['class' => 'form-inline']) ?>
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
                <?= Html::endForm();?>
            </div>
        </div>
        <table class="table table-hover table-bordered text-align-center">
            <thead class="bordered-blue">
            <tr>
                <th class="text-align-center">序号</th>
                <th class="text-align-center">登录名</th>
                <th class="text-align-center">用户名称</th>
                <th class="text-align-center">推荐码</th>
                <th class="text-align-center">累计提现金额</th>
                <th class="text-align-center">累计云豆</th>
                <th class="text-align-center">剩余云豆</th>
                <th class="text-align-center">员工数</th>
                <th class="text-align-center">新增时间</th>
                <th class="text-align-center">地址</th>
                <th class="text-align-center">联系人</th>
                <th class="text-align-center">电话</th>
                <th class="text-align-center">邮件</th>
                <th class="text-align-center">qq</th>
                <th class="text-align-center">微信</th>
                <th class="text-align-center">状态</th>
                <th class="text-align-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($users as $user):?>
                <tr>
                    <td><?= $user->userId ?></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->nickname ?></td>
                    <td><?= $user->recommendCode ?></td>
                    <td><?= Money::findTotalWithdraw($user->userId) ?></td>
                    <td><?= IncomeConsume::findTotalIncome($user->userId) ?></td>
                    <td><?= $user->bitcoin ?></td>
                    <td><?= count(Users::findBeRecommend($user->userId)) ?></td>
                    <td><?= $user->registerDate ?></td>
                    <td><?= $user->address ?></td>
                    <td><?= $user->realname ?></td>
                    <td><?= $user->cellphone ?></td>
                    <td><?= $user->email ?></td>
                    <td><?= $user->qq ?></td>
                    <td><?= $user->weixin ?></td>
                    <td><?= $user->stateName ?></td>
                    <td>
                        <button class="btn btn-xs btn-default update_user_aaa" data-id="<?=$user->userId?>">
                            <span class="fa fa-edit"></span>编辑
                        </button>
                        <?php
                            if($user->state == Users::STATE_FROZEN){
                                $btn_1 = "正常";
                                $state_1 = Users::STATE_NORMAL;
                                $btn_2 = "终止";
                                $state_2 = Users::STATE_STOP;
                            }elseif($user->state == Users::STATE_NORMAL){
                                $btn_1 = "冻结";
                                $state_1 = Users::STATE_FROZEN;
                                $btn_2 = "终止";
                                $state_2 = Users::STATE_STOP;
                            }elseif($user->state == Users::STATE_STOP){
                                $btn_1 = "正常";
                                $state_1 = Users::STATE_NORMAL;
                                $btn_2 = "冻结";
                                $state_2 = Users::STATE_FROZEN;
                            }else{
                                $btn_1 = "未知";
                                $state_1 = "未知";
                                $btn_2 = "未知";
                                $state_2 = "未知";
                            }
                        ?>
                        <button class="btn btn-xs btn-default state_aaa" data-id="<?=$user->userId?>" data-state="<?=$state_1?>"><?=$btn_1?></button>
                        <button class="btn btn-xs btn-default state_aaa" data-id="<?=$user->userId?>" data-state="<?=$state_2?>"><?=$btn_2?></button>
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
