<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\TestLibrary */

$this->title = 'Update Test Library: ' . ' ' . $model->testLibraryId;
$this->params['breadcrumbs'][] = ['label' => 'Test Libraries', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->testLibraryId, 'url' => ['view', 'id' => $model->testLibraryId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="test-library-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
