<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 4:53 PM
 */

namespace backend\components;


use backend\helpers\Html;

class ActiveForm extends \yii\bootstrap\ActiveForm
{
    public $layout = 'horizontal';
    public $fieldConfig = [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'offset' => 'col-sm-offset-2',
        ],
    ];
    public function init()
    {
        Html::addCssClass($this->options, 'mz-main-form');
        parent::init();
    }
}
