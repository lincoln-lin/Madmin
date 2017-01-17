<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/31/16
 * Time: 5:28 PM
 */

namespace backend\components;


use yii\web\Cookie;

class Pagination extends \yii\data\Pagination
{
    public $pageSizeLimit = [1, 100];

    public function init()
    {
        $this->defaultPageSize = \Yii::$app->request->cookies->getValue('_pagesize', 10);
        parent::init();
    }

    public function setPageSize($value, $validatePageSize = false)
    {
        parent::setPageSize($value, $validatePageSize);
        if ($this->pageSize != $this->defaultPageSize) {
            \Yii::$app->response->cookies->add(new Cookie([
                'name' => '_pagesize',
                'value' => $this->pageSize,
                'expire' => time() + 86400 * 3650 // 10年，过期时间的 cookie
            ]));
        }
    }
}
