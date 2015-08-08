<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TestLibrary */

$this->title = $model->testLibraryId;
$this->params['breadcrumbs'][] = ['label' => 'Test Libraries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-library-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->testLibraryId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->testLibraryId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'testLibraryId',
            'provenceId',
            'testTypeId',
            'majorJobId',
            'preTypeId',
            'testChapterId',
            'problem',
            'question',
            'options',
            'answer',
            'analysis',
            'picture',
            'score',
            'status',
            'orderNo',
            'createDate',
            'createUserId',
            'remark',
        ],
    ]) ?>

</div>
