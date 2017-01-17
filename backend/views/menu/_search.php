<?php

use backend\helpers\Html;
use backend\components\ActiveSearchForm as ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenuSearch */
/* @var $form ActiveForm */
?>

<div class="admin-menu-search">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'pid')->label('所属模块')->dropDownList($model->getRootParents(), ['prompt' => '']) ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'url') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置', ['index'],['class' => 'btn btn-default']) ?>
        <?= Html::popup('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
