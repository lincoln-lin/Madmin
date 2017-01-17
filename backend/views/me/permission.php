<?php

use yii\helpers\Html;
use backend\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUser */
/* @var $form backend\components\ActiveForm */

$this->title = '我的权限';
?>

<?= $this->render('../_permission', ['dataProvider' => $dataProvider, 'model' => null]) ?>