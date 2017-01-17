<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use backend\helpers\Html;
use backend\components\ActiveSearchForm as ActiveForm;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->searchModelClass, '\\') ?> */
/* @var $form ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    if (++$count < 6) {
        echo "    <?= " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    } else {
        echo "    <?php // echo " . $generator->generateActiveSearchField($attribute) . " ?>\n\n";
    }
}
?>

    <div class="form-group">
        <?= "<?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?>\n"?>
        <?="<?= Html::a('重置', ['index'],['class' => 'btn btn-default']) ?>\n"?>
        <?="<?= Html::popup('添加', ['create'], ['class' => 'btn btn-success']) ?>\n"?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
