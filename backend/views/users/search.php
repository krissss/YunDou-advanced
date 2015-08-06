<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', '用户管理');
$this->params['breadcrumbs'][] = $this->title;
?>

  <a href="<?=Url::to(['users/create'])?>" ><button type="button" class="btn  btn-small" style="font-size: 13px">
  <span class="glyphicon glyphicon-plus"></span>
</button></a>

<button type="button" class="btn  btn-small" data-toggle="collapse" data-target="#demo"  style="font-size: 13px">
  <span class="glyphicon glyphicon-search"></span>
</button>
<div id="demo" class="collapse">
<div>
    <br>
      <?= Html::beginForm(['users/search'], 'post', ['class'=>'form-horizontal']) ?>
<table class="table table-striped text-align=center">
<tr>
    <td class="small">
        查找
    </td>
<td >
<select class="form-control"  name="search_field" style="width:150px">
  <option value="username">用户名</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option>4</option>
  <option>5</option>
</select></td>
<td>
     <input type="text" name="content" class="form-control" placeholder="请输入查找内容" >
  </div>
</td>
<td>
<a>
<button type="submit" class="btn  btn-small btn btn-primary" >查找</button>
</a>
</td>
</tr>
</table>

</div>
</div>
<div class="users-index" >
    <br>
    <table class="table table-hover table-bordered table table-striped text-align-center">
        <thead>
            <tr>
                <th class="text-align-center">用户名</th>
                <th class="text-align-center">昵称</th>
                <th class="text-align-center">邮箱</th>
                <th class="text-align-center">手机号</th>
                <th class="text-align-center">微信号</th>
                <th class="text-align-center">专业岗位号</th>
                <th class="text-align-center">云豆数</th>
                <th class="text-align-center">省</th>
                <th class="text-align-center">市</th>
                <th class="text-align-center">单位</th>
                <th class="text-align-center">注册时间</th>
                <th class="text-align-center">操作</th>
            </tr>
        </thead>
        <tbody>
         <?php
        foreach ($user as $users): ?>
        <tr class="<?=$users->userId?>">
            <td><?=$users->username?></td>
            <td><?=$users->nickname?></td>
            <td><?=$users->email?></td>
            <td><?=$users->cellphone?></td>
            <td><?=$users->weixin?></td>
            <td><?=$users->majorJobId?></td>
            <td><?=$users->bitcoin?></td>
            <td><?=$users->province?></td>
            <td><?=$users->city?></td>
            <td><?=$users->company?></td>
            <td><?=$users->registerDate?></td>
                <td>
                    <a href="<?=Url::to(['users/view','userId'=>$users->userId])?>" ><span class="glyphicon glyphicon-eye-open">  </span></a>
                    <a href="<?=Url::to(['users/update','userId'=>$users->userId])?>" ><span class="glyphicon glyphicon-pencil">  </span></a>
                    <!--<a href="#" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> 重做</a>-->
                    <!-- <a href=""  data-src="<?=Url::to(['users/delete','userId'=>$users->userId])?>"><button type="button" class="btn btn-danger btn-sm">删除</button></a> -->
                
                                <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'userId' => $users->userId], [
            'data' => [
                'confirm' => '你确定要删除这条信息记录吗？',
                'method' => 'post',
            ],
        ]) ?>

                </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
     <?= Html::endForm() ?>
        <nav class="pull-right pagination_footer">
        <?php echo \yii\widgets\LinkPager::widget([
            'pagination' => $pages,
        ]);?>
    </nav>


</div>
