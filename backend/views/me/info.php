<?php

use yii\helpers\Html;
use backend\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUser */
/* @var $form backend\components\ActiveForm */

$this->title = '个人信息';
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <fieldset><legend>基本信息</legend>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => 'disabled']) ?>
        <?= $form->field($model, 'realname')->textInput(['disabled' => 'disabled']) ?>
        <?= $form->field($model, 'roleDesc')->textarea(['disabled' => 'disabled'])->hint(Html::a('查看我的权限', ['permission'])) ?>
        <?= $form->field($model, 'status')->dropDownList($model::status(), ['disabled' => 'disabled']) ?>
    </fieldset>

    <fieldset><legend>联系方式</legend>
        <?= $form->field($model, 'phone')->textInput() ?>
    </fieldset>

    <fieldset><legend>其它信息</legend>
        <?= $form->field($model, 'last_login_ip')->textInput(['disabled' => 'disabled']) ?>
        <?= $form->field($model, 'last_login_time_txt')->textInput(['disabled' => 'disabled'])->label('最后登录时间') ?>
        <?= $form->field($model, 'remark')->textarea(['rows' => 3]) ?>
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
