<?php

use backend\helpers\Html;
use backend\components\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminMenuSearch */
/* @var $dataProvider backend\components\GridView */

$this->title = '菜单管理';

?>
<div class="admin-menu-index">

    <?= $this->render('_search', ['model' => $searchModel]) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
            [
                'attribute' => 'name',
                'value' => function($model) {
                    return str_repeat('　', $model->level * 2) . $model->name;
                }
            ],
            [
                'attribute' => 'url',
                'format' => 'html',
                'value' => function($model) {
                    $url = ($model['is_route'] ? \yii\helpers\Url::to([$model['url']]) : $model['url']);
                    return str_repeat('　', $model->level * 2) . Html::a($url, $url);
                }
            ],
            [
                'attribute' => 'order',
                'value' => function($model) {
                    return str_repeat('　', $model->level * 2) . $model->order;
                }
            ],
            [
                'class' => \backend\components\ActionColumn::className(),
                'template' => '{update} {create} {delete}',
                'buttons' => [
                    'create' => function($url, $model, $key){
                        return Html::popup('<span class="glyphicon glyphicon-plus"></span> 添加子菜单', ['create', 'pid' => $model->id], ['class' => 'btn btn-default btn-xs']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
