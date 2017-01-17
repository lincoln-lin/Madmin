<?php

use backend\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLog */

$this->title = 'Update User Log: ' . $model->id;
?>
<div class="user-log-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
