<?php

use yii\helpers\Html;
use backend\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUser */
/* @var $form backend\components\ActiveForm */

$this->title = '修改密码';
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <fieldset>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
        <?= $form->field($model, 'password_old')->passwordInput() ?>
        <?= $form->field($model, 'password')->passwordInput()->label('新密码') ?>
        <?= $form->field($model, 'password_new2')->passwordInput() ?>
    </fieldset>

    <div class="action-area">
    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-6">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '保存修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
