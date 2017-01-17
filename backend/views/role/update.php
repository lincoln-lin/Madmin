<?php

/**
 * 这个文件演示了 block 和 Tabs 的用法
 */

/**
 * @var $this  yii\web\View
 * @var $model backend\models\Role
 * @var $dataProvider yii\data\ActiveDataProvider
 */

use kartik\select2\Select2;
use backend\components\ActiveForm;
use yii\helpers\Html;

?>

<?php $this->beginBlock('model-form') ?>
<?= $this->render('_form', ['model' => $model]) ?>
<?php $this->endBlock() ?>


<?php $this->beginBlock('permission') ?>
<?= $this->render('permission', [
    'model' => $model,
    'dataProvider' => $dataProvider,
]) ?>
<?php $this->endBlock() ?>


<?=
\yii\bootstrap\Tabs::widget([
    'items' => [
        [
            'label' => '用户组信息',
            'content' => $this->blocks['model-form'],
        ],
        [
            'label' => '权限设置',
            'content' => $this->blocks['permission'],
        ],
    ],
]) ?>

<?php
$this->title = '更新用户组：' . $model->name;
$this->params['breadcrumbs'][] = $this->title;
