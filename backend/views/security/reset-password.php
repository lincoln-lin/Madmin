<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\ResetPasswordForm */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->params['breadcrumbs'][] = $this->title;
\backend\assets\AdminAsset::register($this);


$this->title = '重置密码';

$this->registerCss($this->render('style.css.php', [
    'bgUrl' =>  \backend\assets\AdminAsset::getAssetUrl('login-bg.jpg'),
    'miezu_uac_syslogo_url' => \backend\assets\AdminAsset::getAssetUrl('meizu.uac.syslogo.png'),
]));

?>


<div class="mz-login-wrap">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= Html::encode($this->title.' - '.Yii::$app->name) ?></h3>
                </div>
                <div class="panel-body">
                        <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                        <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label('请输入新密码') ?>

                        <div class="form-group">
                            <?= Html::submitButton('提交', ['class' => 'btn btn-primary btn-block']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
