<?php

use backend\helpers\Html;
use backend\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLog */
/* @var $form ActiveForm */
?>

<div class="user-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'uid')->textInput() ?>

    <?= $form->field($model, 'ip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'action')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'data')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'log_time')->textInput() ?>

    <div class="action-area">
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'])?>                <?= Html::resetButton('重置表单', ['class' => 'btn btn-default'])?>            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
