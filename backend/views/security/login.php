<?php

use yii\bootstrap\BootstrapPluginAsset;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\LoginForm $model
 */

$this->title = '登录';
$this->params['breadcrumbs'][] = $this->title;
\backend\assets\AdminAsset::register($this);
BootstrapPluginAsset::register($this);

$this->registerCss($this->render('style.css.php', [
    'bgUrl' =>  \backend\assets\AdminAsset::getAssetUrl('login-bg.jpg'),
    'miezu_uac_syslogo_url' => \backend\assets\AdminAsset::getAssetUrl('meizu.uac.syslogo.png'),
]));

$this->registerJs($this->render('login.js'));
?>
<?php $this->registerCss('.mz-message{left:44%;}') ?>
<?= $this->render('../_alert.php') ?>
<div class="mz-login-wrap">
<div class="row">
    <div class="col-md-4 col-md-offset-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title.' - '.Yii::$app->name) ?></h3>
            </div>
            <div class="panel-body">
                <?php
                $form = ActiveForm::begin() ?>

                <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control']]) ?>

                <?= $form->field($model, 'password', ['inputOptions' => ['class' => 'form-control']])->passwordInput() ?>

                <?= $form->field($model, 'verifyCode')->widget(\yii\captcha\Captcha::className(), [
                    'captchaAction' => 'security/captcha',
                    'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-9">{input}</div></div>',
                ])->label('验证码') ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                <?= Html::submitButton('登录', ['class' => 'btn btn-primary btn-block']) ?>
                    <span id="resetPassword" href="" data-toggle="modal" data-target="#resetPasswordModal" style="cursor: pointer;text-decoration: underline">忘记密码?</span>
                </div>

                <?php ActiveForm::end(); ?>

                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['auth'],
                    'popupMode' => false,
                ]) ?>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPassword">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">重置密码</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="login-email">登录邮箱</label>
                        <input type="text" class="form-control" id="login-email">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary" onclick="resetPassword()">发送密码重置邮件</button>
            </div>
        </div>
    </div>
</div>

<script>
    function resetPassword()
    {
        var email = $('#login-email').val()
        if (email) {
            mz.post('request-password-reset',{email:email,action:'send-reset-password-email'}).success(function (err) {
                if (err) {
                    alert(err)
                } else {
                    alert('已经发送一封含有重置密码链接的邮件到您的邮箱, 请查收')
                    $('#resetPasswordModal').modal('toggle')
                }
            })
        }
    }
</script>