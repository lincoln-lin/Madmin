<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/31/16
 * Time: 3:13 PM
 */

namespace backend\components;


use backend\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

class LinkPager extends \yii\widgets\LinkPager
{
    public $hideOnSinglePage = false;
    public $prevPageLabel = 'Prev';
    public $nextPageLabel = 'Next';
    public $firstPageLabel = 'First';
    public $lastPageLabel = 'Last';

    public function run()
    {
        $this->registerJs();

        ob_start();
        parent::run();
        $pagination = ob_get_clean();

        $html = substr($pagination, 0, -5) . '<li><span style="color:#777">'.sprintf('共 %s 条，%s/%s 页', $this->pagination->totalCount, $this->pagination->page + 1, $this->pagination->pageCount).'</span></li>' . '</ul>';

        $li = '';
        foreach ([10,20,50,100] as $pageSize) {
            $li .= "<li><a href='".$this->url($pageSize)."'>$pageSize 条/页</a></li>";
        }
        $elems[] = <<<HTML
<!-- Split button -->
<div class="btn-group dropup">
  <button type="button" class="btn btn-default">{$this->pagination->pageSize} 条/页</button>
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu">
    $li
    <li role="separator" class="divider"></li>
    <li><a href="#" class="cmd-pagesize">自定义</a></li>
  </ul>
</div>
HTML;
        $elems[] = <<<HTML
        <div class="btn-group" style="width: 90px">
<div class="input-group">
      <input type="text" class="form-control input-page">
      <span class="input-group-btn">
        <button class="btn btn-default cmd-page-go" type="button">Go</button>
      </span>
    </div>
    
    </div>
HTML;
        $mzHtml = Html::tag('div', implode("\n", $elems), ['class' => 'pagination', 'style' => 'margin-left:5px;']);

        echo Html::tag('div', $html.$mzHtml, ['class' => 'mz-pagination']);
    }

    protected function url($pageSize)
    {
        return Url::current([$this->pagination->pageSizeParam => $pageSize]);
    }

    protected function registerJs()
    {
        $url = Url::current([
            $this->pagination->pageSizeParam => '_PAGESIZE_',
            $this->pagination->pageParam => '_PAGE_',
        ]);
        $js = <<<JS
var mz_CURRENT_URL = '{$url}';
var mz_CURRENT_URL_PAGESIZE = '{$this->pagination->pageSize}'; 
var mz_CURRENT_URL_PAGE = '{$this->pagination->page}'; 
JS;

        \Yii::$app->view->registerJs($js, View::POS_BEGIN);
    }
}
