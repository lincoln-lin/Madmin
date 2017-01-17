<?php

use backend\helpers\Html;
use backend\components\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

if (Yii::$app->requestedAction->controller->id == 'user-child') {
    $this->title = '设置子账号的内容权限';
} else {
    $this->title = '设置用户组的内容权限: ' . $role_name;
}

$this->registerJs($this->render('content-permission.js.php'));
$this->registerJs($this->render('_category-permission.js'));
?>
<div class="category-index">
    <?= Html::beginForm('', 'post', ['class' => 'form-horizontal mz-main-form']) ?>
    <?= GridView::widget([
        'gridViewExpand' => true,
        'rowOptions' => function ($model, $key, $index, $grid) {
            return [
                'class' => 'child pid-'.$model->pid,
                'data-child-of' => $model[$grid->gridViewExpand['parent']],
            ];
        },
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'name',
                'value' => function($model) {
                    return str_repeat('　', $model->level * 2) . $model->name;
                }
            ],
            [
                'attribute' => 'order',
                'format' => 'text',
            ],
            [
                'attribute' => 'count_article',
                'format' => 'text',
            ],
            [
                'contentOptions' => ['data-type' => 'is_read'],
                'label' => '查看内容权限',
                'format' => 'raw',
                'value' => function($category) use ($permissions){
                    return sprintf('<input type="checkbox" name="category[%s][is_read]" %s>', $category->id, isset($permissions[$category->id]) && $permissions[$category->id]->is_read ? 'checked' : '');
                }
            ],
            [
                'contentOptions' => ['data-type' => 'is_write'],
                'label' => '编辑内容权限',
                'format' => 'raw',
                'value' => function($category) use ($permissions){
                    return sprintf('<input type="checkbox" name="category[%s][is_write]" %s>', $category->id, isset($permissions[$category->id]) && $permissions[$category->id]->is_write ? 'checked' : '');
                }
            ],
            [
                'contentOptions' => ['data-type' => 'is_manage'],
                'label' => '管理员权限',
                'format' => 'raw',
                'value' => function($category) use ($permissions){
                    return sprintf('<input type="checkbox" name="category[%s][is_manage]" %s>', $category->id, isset($permissions[$category->id]) && $permissions[$category->id]->is_manage ? 'checked' : '');
                }
            ],
        ],
    ]); ?>
    <div class="action-area">
        <div class="form-group">
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?= Html::endForm() ?>
</div>
