<?php

use backend\components\GridView;
use backend\Helper;
use backend\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdminUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '用户列表';
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
            ['class' => 'backend\components\CheckboxColumn'],

//            'id',
//            'email',
//            'password_hash',
//            'password_reset_token',
            [
                'attribute' => 'email',
//                'format' => 'text',
            ],
            'realname',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function($model) {
                    return $model->getStatusLabel();
                },
                'options' => ['style' => 'width:3em']
            ],
            /*[
                'attribute' => 'role',
                'format' => 'raw',
                'value' => function($model) use ($rolesMap, $aps){
                    return implode('<br>', array_map(function($name) use ($aps){
                        $expand = Helper::checkRoute('/role/update') ?
                                Html::popup('编辑用户组', ['role/update', 'name' => $name], ['class' => 'btn btn-default btn-xs']) : '';

                        return Html::a($name, ['', 'AdminUserSearch[role]' => $name])
                            . ' ' .
                        (isset($aps[$name]) ? $aps[$name]->getExpireLabel() : Html::tag('label', '永不过期', ['class' => 'label label-success']))
                            . ' ' .
                        sprintf('<span class="expand">%s</span>', $expand);

                    }, isset($rolesMap[$model->id]) ? $rolesMap[$model->id] : []));
                },
            ],*/
            [
                'attribute' => 'phone',
                'options' => ['style' => 'width:8em']
            ],
            [
                'attribute' => 'last_login_time',
                'format' => 'datetime',
                'options' => ['style' => 'width:11em']
            ],
            [
                'attribute' => 'last_login_ip',
                'options' => ['style' => 'width:10em']
            ],
            // 'created_at',
            // 'updated_at',

            ['class' => 'backend\components\ActionColumn',
                'template' => '{update}',
            ],
        ],
    ]); ?>
    <?php \yii\widgets\Pjax::end() ?>
</div>

<script>
    var disableUser = function () {
        var keys = []
        $('[type="checkbox"]:checked').not('.select-on-check-all').each(function () {
            keys.push($(this).val())
        })
        if (keys.length) {
            mz.post({
                action: 'disable-user',
                keys: keys
            }).done(function () {
                window.location.reload()
            })
        }
    }
</script>
