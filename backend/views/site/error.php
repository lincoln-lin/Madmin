<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use backend\helpers\Html;
use yii\web\ForbiddenHttpException;

$action_id = Yii::$app->requestedAction->uniqueId;

$this->title = $name;
?>
<div class="site-error">

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <?php if ($exception instanceof ForbiddenHttpException): ?>
    <p>
        您正在访问 <?= Html::a(Yii::$app->requestedAction->uniqueId, ['/'.$action_id]) ?> , 如有必要, 请联系管理员为您开通对应权限。
        <?= Html::popup('点击查看个人信息', ['me/info']) ?>
    </p>
    <?php endif; ?>

    <p>
        如果您认为这是系统bug,请反馈给系统开发测试人员。
    </p>

</div>
