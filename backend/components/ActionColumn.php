<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/18/16
 * Time: 4:02 PM
 */

namespace backend\components;

use Yii;
use backend\helpers\Html;
use yii\helpers\ArrayHelper;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $header = '操作';

    public $buttonLabels = [];

    public function init()
    {
        $this->contentOptions = ArrayHelper::merge([
            'style' => [
                'text-align' => 'center',
            ]
        ], $this->contentOptions);

        parent::init();
    }

    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'View'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-xs',
                ], $this->buttonOptions);
                return Html::popup('<span class="glyphicon glyphicon-eye-open"></span> ' . Yii::t('yii', 'View'), $url, $options);
            };
        }
        if (!isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Update'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                    'class' => 'btn btn-default btn-xs',
                ], $this->buttonOptions);
                return Html::popup('<span class="glyphicon glyphicon-pencil"></span> 编辑', $url, $options);
            };
        }
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                $options = array_merge([
                    'title' => Yii::t('yii', 'Delete'),
                    'aria-label' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                    'class' => 'btn btn-danger btn-xs',
                ], $this->buttonOptions);
                return Html::a('<span class="glyphicon glyphicon-trash"></span> ' . ( isset($this->buttonLabels['delete']) ? $this->buttonLabels['delete'] : Yii::t('yii', 'Delete')), $url, $options);
            };
        }
    }
}
