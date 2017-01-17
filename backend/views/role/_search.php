<?php

use backend\helpers\Html;
use backend\components\ActiveSearchForm as ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminMenuSearch */
/* @var $form ActiveForm */
?>

<div class="admin-role-search">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'status')->dropDownList([1=>'未过期', 2=>'已过期'], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary', 'onclick' => '$(".gridViewExpand").gridViewExpand("search", $("#authitemsearch-name").val());return false']) ?>
        <?= Html::a('重置', ['index'],['class' => 'btn btn-default', 'onclick' => '$(".gridViewExpand").gridViewExpand("reset");return false']) ?>
        <?= Html::popup('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
