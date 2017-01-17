<?php

use yii\helpers\Html;
use backend\components\ActiveSearchForm as ActiveForm;
use backend\components\DateTimePicker;
use yii\log\Logger;

/* @var $this yii\web\View */
/* @var $model backend\models\LogSearch */
/* @var $form ActiveForm */
?>

<div class="log-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'level')->dropDownList([
        Logger::LEVEL_ERROR => 'error',
        Logger::LEVEL_WARNING => 'warning',
    ], ['prompt' => '']); ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'log_time_start')->widget(DateTimePicker::className(), [
        'type' => DateTimePicker::TYPE_INPUT
    ])->label('时间') ?>

    <?= $form->field($model, 'log_time_end')->widget(DateTimePicker::className(), [
        'type' => DateTimePicker::TYPE_INPUT
    ])->label('') ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置', ['index'], ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
