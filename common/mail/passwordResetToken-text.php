<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['security/reset-password', 'token' => $user->password_reset_token]);
?>
您好 <?= $user->username ?>,

点击下面链接重置您的密码:

<?= $resetLink ?>
