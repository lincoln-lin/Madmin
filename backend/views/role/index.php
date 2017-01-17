<?php

/**
 * @var $dataProvider array
 * @var $filterModel  backend\models\AuthItemSearch
 * @var $this         yii\web\View
 */


use backend\components\ActionColumn;
use backend\components\GridView;
use backend\Helper;
use backend\models\AuthItemProfile;
use yii\helpers\Url;
use backend\helpers\Html;
use yii\rbac\Item;

$this->title = '用户组管理';

?>

<?= $this->render('_search', ['model' => $filterModel]) ?>
<?php \yii\widgets\Pjax::begin() ?>
<?= GridView::widget([
    'gridViewExpand' => true,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $p = substr($model['name'], 0, strrpos($model['name'], '-'));
        return [
            'class' => "child",
            'data-child-of' => $p,
        ];
    },
    'dataProvider' => $dataProvider,
    'columns'      => [
        [
            'label' => '名称',
            'format' => 'raw',
            'value' => function($role){
                return Html::a($role['name'], ['user/index', 'AdminUserSearch[role]' => $role['name']], ['style' => 'margin-left:'.(substr_count($role['name'], '-')*2).'em;']);
            }
        ],
        [
            'attribute' => 'description',
            'label' => '描述',
        ],
        [
            'label' => '子用户组',
            'format' => 'raw',
            'value' => function ($role) use ($childs) {
                $children = isset($childs[$role['name']]) ? $childs[$role['name']] : [];
                if (!empty($children)) {
                    return implode('，', array_map(function($role){
                        return Html::popup($role, ['role/update', 'name' => $role]);
                    }, array_keys(array_filter($children, function($item){
                        return $item['type'] == Item::TYPE_ROLE;
                    }))));
                }
                return '';
            }
        ],
        [
            'format' => 'raw',
            'label' => '过期时间',
            'value' => function($model) use ($profiles){
                $profile = isset($profiles[$model['name']]) ? $profiles[$model['name']] : null;
                if ($profile) {
                    return $profile->getExpireLabel();
                }
                return Html::tag('label', '永不过期', ['class' => 'label label-success']);
            },
            'options' => ['style' => 'width:6em']
        ],
        [
            'class'      => ActionColumn::className(),
            'urlCreator' => function ($action, $model) {
                return Url::to([$action, 'name' => $model['name']]);
            },
            'template'   => '{update} {permission} {delete}',
            'buttons' => [
                'permission' => function ($url, $model, $key){
                    return Html::popup('设置系统权限', $url, ['class' => 'btn btn-default btn-xs']);
                }
            ],
            'visibleButtons' => [
                'update' => Helper::checkRoute('/role/update'),
                'permission' => Helper::checkRoute('/role/permission'),
                'delete' => Helper::checkRoute('/role/delete'),
            ],
            'options' => ['style' => 'width:16em']
        ]
    ],
]) ?>
<?php \yii\widgets\Pjax::end() ?>
