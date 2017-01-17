<?php

use yii\helpers\Html;
use backend\components\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminUser */
/* @var $form backend\components\ActiveForm */

$this->registerJs($this->render('script.js'));
?>

<div class="admin-user-form">

    <?php $form = ActiveForm::begin(); ?>

    <fieldset><legend>登录信息</legend>
    <?=
    $model->isNewRecord ?
    $form->field($model, 'email')->textInput(['maxlength' => true])
        :
    $form->field($model, 'email')->textInput(['maxlength' => true, 'disabled' => true])
    ?>

    <?=
    $model->isNewRecord ?
        $form->field($model, 'password')->passwordInput()
        :
        $form->field($model, 'password')->passwordInput()->hint('密码留空为不修改密码') ?>
    </fieldset>

    <fieldset><legend>状态信息</legend>
        <?= $form->field($model, 'status')->dropDownList($model::status()) ?>
        <?= $form->field($model, 'role')->widget(\kartik\select2\Select2::className(), [
            'showToggleAll' => false,
            'data' => (new \backend\models\Role())->getRoles(),
            'options' => [
                'multiple' => true,
            ]
        ]) ?>
        <?php if (!$model->isNewRecord): ?>

            <div class="form-group field-adminuserform-unlock_time">
                <label class="control-label col-sm-2" for="adminuserform-unlock_time">帐号解锁时间</label>
                <div class="col-sm-6">
                    <div class="input-group"><input type="text" id="adminuserform-unlock_time" class="form-control" value="<?= date('Y-m-d H:i:s', $model->unlock_time) ?>" disabled=""><span class="input-group-btn"><span class="btn btn-default cmd-unlock">解锁</span></span></div>
                    <div class="help-block help-block-error "></div>
                </div>
            </div>

        <?php endif; ?>
    </fieldset>

    <fieldset><legend>个人信息</legend>
        <?= $form->field($model, 'realname')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'phone')->textInput() ?>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true]) ?>
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
