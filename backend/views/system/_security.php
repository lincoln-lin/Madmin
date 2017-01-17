<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/30/16
 * Time: 2:23 PM
 */

/**
 * @var $this \yii\web\View
 * @var $model \backend\models\KeyStorage
 * @var $form backend\components\ActiveForm
 */
?>
<fieldset><legend>Http Basic Auth</legend>
<?= $form->field($model, 'AUTH_USER') ?>
<?= $form->field($model, 'AUTH_PASSWORD') ?>
</fieldset>