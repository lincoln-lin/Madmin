<?php

use backend\helpers\Html;
use backend\components\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '操作日志';
?>
<div class="user-log-index">

    <?= $this->render('_search', ['model' => $searchModel]) ?>
<?php \yii\widgets\Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'format' => 'text',
            ],
            [
                'attribute' => 'user.realname',
                'format' => 'text',
                'options' => [
                    'style' => 'width:75px',
                ],
            ],
            [
                'attribute' => 'log_time',
                'format' => 'datetime',
                'label' => '操作时间',
            ],
            [
                'attribute' => 'ip',
                'format' => 'text',
                'label' => 'IP',
            ],
            [
                'attribute' => 'action_name',
                'format' => 'text',
            ],
            [
                'attribute' => 'action',
                'format' => 'text',
            ],
            [
                'attribute' => 'data',
                'format' => 'ntext',
            ],
/*
            [
                'class' => 'backend\components\ActionColumn',
            ],*/
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>
