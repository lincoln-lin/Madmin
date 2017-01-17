<?php

use yii\helpers\Json;
use backend\components\GridView;
use yii\web\View;
use backend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '设置用户组权限：' . $model->name;
?>

<?= Html::beginForm(['permission', 'name' => $model->name]) ?>

<div>
    <?= Html::submitButton('添加选中的项', ['class' => 'btn btn-success']) ?>
</div>

<div class="mz-route-index">
    <?= $this->render('../_permission', ['dataProvider' => $dataProvider, 'model' => $model]) ?>
</div>

<div>
    <?= Html::submitButton('添加选中的项', ['class' => 'btn btn-success']) ?>
</div>

<?= Html::endForm() ?>
