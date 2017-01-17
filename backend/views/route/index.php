<?php

use yii\helpers\Json;
use backend\components\GridView;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '路由权限';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mz-route-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'route',
                'format' => 'raw',
                'value' => function($model) {
                    /** @var \backend\models\Route $model */
                    return str_repeat('　', $model->getLevel() * 2) . $model->route;
                }
            ],
            [
                'attribute' => 'menu.name',
                'value' => function($model) {
                    return $model->menu->name ? str_repeat('　', $model->getLevel() * 2) . $model->menu->name
                        : '';
                }
            ],
            [
                'attribute' => 'permission.description',
                'value' => function($model) {
                    return $model->permission->description ? str_repeat('　', $model->getLevel() * 2) . $model->permission->description
                        : '';
                }
            ],
            [
                'class' => \backend\components\ActionColumn::className(),
                'template' => '{update} {delete}',
                'buttonLabels' => [
                    'delete' => '删除菜单',
                ],
            ],
        ],
    ])
    ?>
</div>
