<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var $this  yii\web\View
 * @var $model backend\models\Role
 */

use kartik\select2\Select2;
use backend\components\ActiveForm;
use backend\components\DateTimePicker;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin([
    'enableClientValidation' => false,
    'enableAjaxValidation'   => true,
]) ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'description') ?>

<?php // $form->field($model, 'rule') ?>

<?= $form->field($model, 'children')->widget(Select2::className(), [
    'data' => $model->getRoles(),
    'theme' => 'bootstrap',
    'options' => [
        'id' => 'children',
        'multiple' => true
    ],
]) ?>

<?= $form->field($model, 'expire')->widget(DateTimePicker::className(), [
    'type' => DateTimePicker::TYPE_INPUT
])->hint('可以为空，为空表示不过期') ?>

<div class="action-area">
    <div class="form-group">
        <div class="col-sm-2"></div>
        <div class="col-sm-6">
            <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end() ?>

