<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 2:29 PM
 */

namespace backend\components;

use backend\assets\GridViewExpandAsset;
use backend\helpers\Html;
use backend\Helper;
use yii\helpers\Json;

class GridView extends \yii\grid\GridView
{
    /**
     * [
     *      'parent' => '',
     *      'expandElementSelector' => '',
     * ]
     * @var array
     */
    public $gridViewExpand = [];

    public $pager = ['class' => 'backend\components\LinkPager'];

    public function init()
    {

        parent::init();

        Html::addCssClass($this->tableOptions, explode(' ', 'table table-striped table-bordered table-hover'));

        if (!empty($this->gridViewExpand)) {
            Html::addCssClass($this->options, 'gridViewExpand');
            Html::removeCssClass($this->tableOptions, 'table-striped');
            $this->gridViewExpand = array_merge([
                'parent' => 'pid',
                'expandElementSelector' => '>:first-child',
            ], (array)$this->gridViewExpand);
            if (empty($this->rowOptions)) {
                $this->rowOptions = function ($model, $key, $index, $grid) {
                    return [
                        'class' => "child",
                        'data-child-of' => $model[$grid->gridViewExpand['parent']],
                    ];
                };
            }

        }
    }

    public function run()
    {
        if (!empty($this->gridViewExpand)) {
            $id = $this->options['id'];
            $view = $this->getView();
            GridViewExpandAsset::register($view);
            $gridViewExpandOptions = Json::htmlEncode($this->gridViewExpand);
            $view->registerJs("jQuery('#$id').gridViewExpand($gridViewExpandOptions);");
        }
        parent::run();
    }
}
