<?php
/* @var $this yii\web\View */
/** @var $model \backend\models\SqlQueryForm */

use backend\components\ActiveForm;
use backend\helpers\Html;
use backend\components\GridView;

$this->title = 'SQL查询';
?>

<div>
    <?php $form = ActiveForm::begin(['method' => 'get', 'action' => ['query']]) ?>

    <?= $form->field($model, 'sql')->textarea(['rows' => 6]) ?>

    <div class="action-area">
        <div class="form-group">     
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>
                <?= Html::a('重置表单', ['query'], ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end() ?>

    <?php if ($dataProvider) : ?>
        <?php \yii\widgets\Pjax::begin() ?>
    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
    ])
    ?>
        <?php \yii\widgets\Pjax::end() ?>
    <?php endif; ?>
</div>
