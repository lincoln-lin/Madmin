<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/11/16
 * Time: 11:15
 */

namespace backend\components;


use backend\helpers\Html;
use kartik\base\InputWidget;
use yii\helpers\Url;

class PopupSelect extends InputWidget
{
    public $popupUrl;

    public $callback;

    public function init()
    {
        parent::init();
        $id = $this->options['id'];
        $this->options['data-popup-url'] = Url::to($this->popupUrl);
        if (empty($this->callback)) {
            $this->callback = "function(data,url){ $('#{$id}').val(data);$('#{$id}').data('popup-url', url) }";
        }
    }

    public function run()
    {
        $this->registerJs();
        Html::addCssClass($this->options, 'form-control');
        $input = $this->getInput('textInput');
        return "<div class='input-group'>{$input}<span class='input-group-btn'><a class='btn btn-default'><span class='glyphicon glyphicon-list'></span></a></span></div>";
    }

    protected function registerJs()
    {
        $id = $this->options['id'];
        $js = <<<JS
$('#{$id}').closest('.input-group').find('.btn').click(function(){
    Utils.OpenCenterWin($('#{$id}').data('popup-url'))
    window.PopupSelect = {$this->callback}
})
JS;
        $this->getView()->registerJs($js);
    }
}
