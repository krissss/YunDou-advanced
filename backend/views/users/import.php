<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', '导入客户' );
$this->params['breadcrumbs'][] = ['label' => '返回', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<table  cellpadding="0" cellspacing="0" width="100%" border=0>
	<thead>
			 <tr class="success">
		<th colspan="2" height="50" valign="middle" align="left" class="detail-content-heading">导入客户：</th>
		</tr>
	</thead>
	<tbody>
	
		  <tr >
				<td colspan="2" align="left" valign="top" style="padding-left:40px;">
					<br>
						<span class="genHeaderGray">第二步(共三步):</span>&nbsp; 
						<span class="genHeaderSmall">  客户 字段映射</span> <br><br>

							<p><font size="2.5" >以下列表显示 客户 和其它信息. 请在下拉框中选择与标题列对应的Yundou系统字段。</font> 
							</p>

					</td>
				   </tr>		   				 		
	</tbody>
</table>
<table  class="table table-bordered">
	   <thead>
      <tr class="success">
         <th>yundou系统字段</th>
         <th>导入字段</th>
         <th>列1</th>
         <th>列2</th>
          </tr>
   </thead>
   <tbody>
    <?php 
    foreach($xls as $xlss): ?>
   	<tr>
   		<td><select class="form-control"  name="select_name" style="width:150px">
  <option value="username">客户名</option>
  <option value="cellphone">手机号</option>
  <option value="city">城市</option>
  <option value="role">客户级别</option>  
  <option value="bitcoin">云豆数</option>
  <option value="company">单位</option>
  <option value="registerDate">注册时间</option>  
  <option value="province">省</option>
  <option value="weixin">微信号</option>
   <option value="password"邮箱>密码</option>
  <option value="email">手机号</option>
  <option value="majorJobId">专业岗位号</option>
  <option value="nickname">昵称</option>  
  <option value="realname">真实姓名</option>
  <option value="introduce">自我简介</option>
  <option value="address">详细地址</option>  
  <option value="recommendUserID">推荐人</option>
  <option value="remark">remark</option>
</select>
   		</td><?=$xls->sheets[0]['cells']?><td>
   		</td>
   		<td>ling
   		</td>
   		<td>qing
   		</td>
   </tbody>
	</table>
	<div align="right">
		 <a href="<?=Url::to(['users/upusers'])?>">
		 	<button type="button" class="btn btn-warning">上一步
		</a></button>
		 <a href="<?=Url::to(['users/importin'])?>" ><button type="button" class="btn btn-success">现在导入</a>
		</button>

	</div>
<?php endforeach;?>