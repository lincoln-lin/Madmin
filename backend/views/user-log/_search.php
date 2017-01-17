<?php

use backend\helpers\Html;
use backend\components\ActiveSearchForm as ActiveForm;
use backend\components\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\UserLogSearch */
/* @var $form ActiveForm */
?>

<div class="user-log-search">

    <?php $form = ActiveForm::begin(); ?>

    <?php // $form->field($model, 'uid')->dropDownList(\backend\models\AdminUser::map(), ['prompt' => '']) ?>

    <?= $form->field($model, 'action') ?>

    <?= $form->field($model, 'log_time_start')->widget(DateTimePicker::className(), [
        'type' => DateTimePicker::TYPE_INPUT
    ])->label('时间') ?>

    <?= $form->field($model, 'log_time_end')->widget(DateTimePicker::className(), [
        'type' => DateTimePicker::TYPE_INPUT
    ])->label('') ?>


    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置', ['index'],['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
