<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
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
	<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
		  <tr >
				<td colspan="2" align="left" valign="top" style="padding-left:40px;">
					<br>
						<span class="genHeaderGray">第一步(共三步):</span>&nbsp; 
						<span class="genHeaderSmall">选择来源</span> 
					</td>
				   </tr>
				   <tr >
					<td colspan="2" align="left" valign="top" style="padding-left:40px;">
						导入文件格式支持Excel2003(.xls)格式。如果导入的文件有格式(例如颜色、锁定、过滤或多个sheet)，yundou系统将不能识别，导入时会出现空白。如果遇到问题，请先用Excel打开文件，另存为XLS格式。
					</td>
				   </tr>
				    <tr ><td align="left" valign="top" colspan="2">&nbsp;</td></tr>
				      <tr>
					<td align="center" valign="top"  class=small><b>请选择要的Excel文件: 
					
							<td><input type="file" name="file"></td>
     			 	<tr>
     			 		<td colspan="2" height="50">&nbsp</td>
     			 	</tr>
		</tr> 
		<tr><td colspan="2" align="right" style="padding-right:40px;"><button type="submit" class="btn btn-primary">下一步</button></td></td>
	<?= Html::endForm() ?>
	</tbody>
</table>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput() ?>

<button>Submit</button>

<?php ActiveForm::end() ?>