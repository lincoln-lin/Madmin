<?php
use backend\components\GridView;
use backend\Helper;
use backend\models\AdminUser;
use backend\models\Role;

/* @var $this yii\web\View */

$columns = [
    [
        'class' => \yii\grid\CheckboxColumn::className(),
        'name' => 'permissions',
        'checkboxOptions' => function ($route, $key, $index, $column) use ($model) {
            $options = [];

            if (isset($model['permissions'])) {
                $options['checked'] = isset($model['permissions']) ? in_array($route->route, $model['permissions']) : Helper::checkRoute($route->route);
            }

            if (Yii::$app->requestedAction->controller->id === 'user-child') { // mz/user-child/permission
                if (!Helper::checkRoute($route->route)) {
                    $options['class'] = 'hide';
                    $options['disabled'] = true;
                }
            }

            return $options;
        }
    ],
    [
        'attribute' => 'permission.description',
        'value' => function($route) {
            return $route->permission->description ? str_repeat('　', $route->getLevel() * 2) . $route->permission->description
                : '';
        }
    ],
];
if (Helper::isRootUser()) {
    $columns[] = [
        'attribute' => 'route',
        'value' => function ($route) {
            /** @var \backend\models\Route $route */
            return str_repeat('　', $route->getLevel() * 2) . $route->route;
        }
    ];
}

$this->registerJs($this->render('_permission.js'));
?>
<?= GridView::widget([
    'rowOptions' => function ($model, $key, $index, $grid) {
        return [
            'class' => "child",
            'data-child-of' => $model->route === '/' ? '' : dirname($model->route),
        ];
    },
    'dataProvider' => $dataProvider,
    'columns' => $columns,
])
?>
