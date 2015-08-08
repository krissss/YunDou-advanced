<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\TestLibrary */

$this->title = 'Create Test Library';
$this->params['breadcrumbs'][] = ['label' => 'Test Libraries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="test-library-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
