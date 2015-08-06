<?php
/* @var $this yii\web\View */
/* @var $model common\models\Users */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="widget flat">
    <div class="widget-body">
        <table class="table table_four_column">
            <tbody>
                <tr>
                    <td class="text-align-right table_bg_grey">微信id</td>
                    <td><?=$model->weixin?></td>
                    <td class="text-align-right table_bg_grey">昵称</td>
                    <td><?=$model->nickname?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">手机号</td>
                    <td><?=$model->cellphone?></td>
                    <td class="text-align-right table_bg_grey">性别</td>
                    <td><?=$model->sex?></td>
                </tr>
                <tr>
                    <td class="text-align-right table_bg_grey">真实姓名</td>
                    <td><?=$model->realname?></td>
                    <td class="text-align-right table_bg_grey">简介</td>
                    <td><?=$model->introduce?></td>
                </tr>
            </tbody>
        </table>
        <p>
            <?= Html::a('修改', ['update', 'userId' => $model->userId], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('删除', ['delete', 'userId' => $model->userId], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => '你确定要删除这条信息记录吗？',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    </div>
</div>

<div class="users-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'username', 
            'userId',
            'realname',
            // 'password',
            'cellphone',
            'bitcoin',
            'role', 
            'province',
            'cityId',
            'company',
            'address',
            'registerDate',
            'weixin',
            'nickname',
            'majorJobId',
            'introduce',
            'recommendUserID',
            'email:email',
            'remark',
        ],
        'options' => ['class' => 'table table-striped table-bordered detail-view table-hover '],
    ]) ?>
     <p>
        <?= Html::a('修改', ['update', 'userId' => $model->userId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'userId' => $model->userId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除这条信息记录吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>
