<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\UserLog */

$this->title = 'Create User Log';
?>
<div class="user-log-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
