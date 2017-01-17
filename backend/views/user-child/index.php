<?php

use backend\components\GridView;
use backend\Helper;
use backend\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '我的子账号';
$this->params['breadcrumbs'][] = $this->title;

$this->registerCss('
.expand {visibility: hidden;}
td:hover .expand {visibility: visible;}
');
?>
<div class="admin-user-index">

    <?php \yii\widgets\Pjax::begin() ?>
    <?= $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'email',
//            'password_hash',
//            'password_reset_token',
            [
                'attribute' => 'email',
                'format' => 'email',
            ],
            'realname',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->getStatusLabel();
                }
            ],
             'phone',
            [
                'attribute' => 'last_login_time',
                'format' => 'relativeTime',
            ],
            'last_login_ip',
            // 'created_at',
            // 'updated_at',

            ['class' => 'backend\components\ActionColumn',
                'template'   => '{update} {permission} {delete}',
                'buttons' => [
                    'permission' => function ($url, $model, $key){
                        return Html::popup('设置系统权限', $url, ['class' => 'btn btn-default btn-xs']);
                    },
                    'content-permission' => function ($url, $model, $key) {
                        return Html::popup('设置内容权限', $url, ['class' => 'btn btn-default btn-xs']);
                    }
                ],
                'visibleButtons' => [
                    'update' => Helper::checkRoute('/user-child/update'),
                    'permission' => Helper::checkRoute('/user-child/permission'),
                    'content-permission' => Helper::checkRoute('/user-child/content-permission'),
                    'delete' => Helper::checkRoute('/user-child/delete'),
                ],
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>
