<?php

use backend\models\AdminUser;
use backend\models\Role;
use yii\helpers\Json;
use backend\components\GridView;
use yii\web\View;
use backend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/** @var $model Role */

$this->title = '设置子账号权限';
?>

<?= Html::beginForm(['permission', 'name' => $model->name]) ?>

<div>
    <?= Html::submitButton('添加选中的项', ['class' => 'btn btn-success']) ?>
</div>

<div class="mz-route-index">
    <?= $this->render('../_permission', ['dataProvider' => $dataProvider, 'model' => $model]) ?>
</div>

<div class="checkbox">
    <?= Html::submitButton('添加选中的项', ['class' => 'btn btn-success']) ?>
</div>

<?= Html::endForm() ?>
