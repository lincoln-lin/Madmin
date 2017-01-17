<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use backend\helpers\Html;
use <?= $generator->indexWidgetType === 'grid' ? "mz\\admin\\components\\GridView" : "yii\\widgets\\ListView" ?>;
<?= $generator->enablePjax ? 'use yii\widgets\Pjax;' : '' ?>

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->modelClass)))) ?>;
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-index">

<?= $generator->enablePjax ? '<?php Pjax::begin(); ?>' : '' ?>

<?php if(!empty($generator->searchModelClass)): ?>
<?= "    <?= "?>$this->render('_search', ['model' => $searchModel]) ?>
<?php endif; ?>

<?php if ($generator->indexWidgetType === 'grid'): ?>
    <?= "<?= " ?>GridView::widget([
        'dataProvider' => $dataProvider,
        <?= false ? "'filterModel' => \$searchModel,\n        'columns' => [\n" : "'columns' => [\n"; ?>
            ['class' => 'yii\grid\SerialColumn'],

<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            ['attribute' => '{$name}'],\n";
            echo "            [\n";
            echo "                'attribute' => '{$name}',\n";
            echo "            ],\n";
        } else {
            echo "            /*\n";
            echo "            [\n";
            echo "                'attribute' => '{$name}',\n";
            echo "            ],//*/\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            [\n";
            echo "                'attribute' => '{$column->name}',\n";
            echo "                'format' => '{$format}',\n";
            echo "            ],\n";
        } else {
            echo "            /*\n";
            echo "            [\n";
            echo "                'attribute' => '{$column->name}',\n";
            echo "                'format' => '{$format}',\n";
            echo "            ],//*/\n";
        }
    }
}
?>

            [
                'class' => 'backend\components\ActionColumn',
            ],
        ],
    ]); ?>
<?php else: ?>
    <?= "<?= " ?>ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['class' => 'item'],
        'itemView' => function ($model, $key, $index, $widget) {
            return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
        },
    ]) ?>
<?php endif; ?>
<?= $generator->enablePjax ? '<?php Pjax::end(); ?>' : '' ?>
</div>
