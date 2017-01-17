<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenu */

$this->title = '更新菜单: ' . $model->name;
?>
<div class="admin-menu-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
