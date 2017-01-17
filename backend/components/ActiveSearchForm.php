<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/23/16
 * Time: 3:06 PM
 */

namespace backend\components;


use backend\Helper;
use backend\helpers\Html;
use yii\widgets\ActiveForm;

class ActiveSearchForm extends ActiveForm
{
    public $action = ['index'];
    public $method = 'get';
    public $enableClientValidation= false;
    
    public function init()
    {
        Html::addCssClass($this->options, ['mz-main-search-form', 'form-inline']);
        parent::init();
    }
}