<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenu */

$this->title = '添加菜单项';
?>
<div class="admin-menu-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
