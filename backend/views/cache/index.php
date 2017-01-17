<?php

use backend\helpers\Html;
use backend\components\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminMenuSearch */
/* @var $dataProvider backend\components\GridView */

$this->title = '缓存';

?>
<?php \yii\widgets\Pjax::begin() ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => \yii\grid\SerialColumn::className()],
        [
            'label' => '组件名称',
            'value' => function($m, $key){
                return $key;
            },
        ],
        [
            'label' => '缓存类型',
            'value' => function($m) {
                return get_class($m);
            }
        ],
        [
            'label' => 'key 前缀',
            'value' => function($model) {
                return trim($model->keyPrefix);
            }
        ],
        [
            'label' => '其他信息',
            'format' => 'ntext',
            'value' => function($m) {
                $info = $m;
                if ($m instanceof \yii\redis\Cache) {
                    $redis = $m->redis;
                    if ($redis->unixSocket) {
                        $keys[] = 'unixSocket';
                    } else {
                        $keys[] = 'hostname';
                        $keys[] = 'port';
                    }
                    $keys[] = 'database';
                    $keys[] = 'connectionTimeout';
                    $keys[] = 'dataTimeout';
                    $info = [];
                    foreach ($keys as $key) {
                        $info[$key] = $redis->$key;
                    }
                }

                return json_encode($info, JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
            }
        ],
        [
            'class' => \backend\components\ActionColumn::className(),
            'template' => '{flush}',
            'buttons' => [
                'flush' => function($url, $model, $key){
                    return Html::a('<span class="glyphicon glyphicon-trash"></span> 清空', $url, [
                        'class' => 'btn btn-danger btn-xs',
                        'data-method' => 'post',
                        'data-confirm' => '确定清空缓存？',
                    ]);
                },
            ],
        ],
    ],
]); ?>
<?php \yii\widgets\Pjax::end() ?>
