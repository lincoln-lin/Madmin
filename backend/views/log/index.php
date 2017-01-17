<?php

use yii\helpers\Html;
use backend\components\GridView;
use yii\log\Logger;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '系统日志';
?>
<div class="log-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
<?php \yii\widgets\Pjax::begin() ?>
    <?= GridView::widget([
        'tableOptions' => [
            'style' => 'table-layout: fixed;word-wrap: break-word;',
        ],
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'rowOptions' => function ($model, $key, $index, $grid) {
            switch ($model['level']) {
                case Logger::LEVEL_ERROR : return ['class' => 'danger'];
                case Logger::LEVEL_WARNING : return ['class' => 'warning'];
                case Logger::LEVEL_INFO : return ['class' => 'success'];
                default: return [];
            }
        },
        'columns' => [
            [
                'attribute' => 'id',
                'options' => [
                    'style' => 'width:80px;',
                ],
            ],
            [
                'options' => [
                    'style' => 'width:80px;',
                ],
                'attribute' => 'level',
                'value' => function ($data) {
                    return Logger::getLevelName($data['level']);
                },
                'filter' => [
                    Logger::LEVEL_TRACE => ' Trace ',
                    Logger::LEVEL_INFO => ' Info ',
                    Logger::LEVEL_WARNING => ' Warning ',
                    Logger::LEVEL_ERROR => ' Error ',
                ],
            ],
            [
                'attribute' => 'log_time',
                'format' => 'raw',
                'value' => function($model){
                    return date('Y-m-d H:i:s', $model->log_time);
                },
                'options' => [
                    'style' => 'width:156px;',
                ]
            ],
            [
                'attribute' => 'category',
                'options' => [
                    'style' => 'width:230px;',
                ],
            ],
//            /*
            [
                'attribute' => 'message',
                'format' => 'ntext',
            ],//*/
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>
