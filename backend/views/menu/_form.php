<?php

use yii\helpers\Html;
use backend\components\ActiveForm as ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenu */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="admin-menu-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pid')->widget(\kartik\select2\Select2::className(), ['data' => array_replace(['-1' => '顶级菜单'], (new \backend\models\AdminMenuSearch())->map()), 'theme' => 'bootstrap',]) ?>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order')->textInput() ?>

    <div class="action-area">
        <div class="form-group">             
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
