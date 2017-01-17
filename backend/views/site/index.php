<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/4/16
 * Time: 10:48
 */

/* @var $this yii\web\View */

use backend\helpers\Html;

$this->title = '后台首页';
?>

<h1><?= Yii::$app->name ?>, 欢迎您</h1>

<p>
    如果您要访问的内容没有显示在页面上, 请联系管理员为您开通对应权限。
    <?= Html::popup('点击查看个人信息', ['me/info']) ?>
</p>
