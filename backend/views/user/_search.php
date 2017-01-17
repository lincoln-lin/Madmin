<?php

use backend\assets\papaparse\PapaParseAsset;
use backend\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUserSearch */
/* @var $form yii\bootstrap\ActiveForm */

PapaParseAsset::register($this);

$this->registerJs($this->render('_search.js'));
?>

<div class="admin-user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'enableClientValidation' => false,
        'options' => ['class' => 'mz-main-search-form form-inline']
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['placeholder' => '邮箱'])->label(false) ?>

    <?= $form->field($model, 'realname')->textInput(['placeholder' => '姓名'])->label(false) ?>

    <?= $form->field($model, 'role')
        ->widget(\kartik\select2\Select2::className(), [
        'data' => array_merge(['　'], (new \backend\models\Role())->getRoles()),
            'pluginLoading' => false,
        'theme' => 'bootstrap',
            'pluginOptions' => [
                'width' => '200px',
            ],
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList($model::status(), ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
        <?= Html::a('重置', ['index'],['class' => 'btn btn-default']) ?>
        <?= Html::popup('添加', ['create'], ['class' => 'btn btn-success']) ?>
        <a class="btn btn-success mz-btn-input-file">导入用户
            <input type="file" id="cmd-import-file">
        </a>
        <?= Html::button('禁用', ['class' => 'btn btn-danger', 'onclick' => 'disableUser();']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
