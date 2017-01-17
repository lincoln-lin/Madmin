<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 4:22 PM
 */

use yii\helpers\Html;
use backend\components\ActiveForm;


/* @var $this yii\web\View */
/* @var $model backend\models\RouteForm */
/* @var $form backend\components\ActiveForm */

$this->title = '更新: ' . $model->route->route;
?>

<div class="route-update-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'menuName')->textInput() ?>
    <?= $form->field($model, 'menuPid')->widget(\kartik\select2\Select2::className(), ['data' => array_replace(['-1' => '顶级菜单'], (new \backend\models\AdminMenuSearch())->map()), 'theme' => 'bootstrap',]) ?>
    <?= $form->field($model, 'permissionDesc')->textInput() ?>

    <div class="action-area">
        <div class="form-group">            
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton('提交', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
