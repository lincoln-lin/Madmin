<?php
namespace backend\helpers;
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/17/16
 * Time: 5:35 PM
 */
use backend\Helper;
use yii\helpers\BaseHtml;

class Html extends BaseHtml
{
    public static function popup($text, $url = null, $options = [])
    {
        $options['onclick'] = 'javascript:mz.popup(this);return false;';
        return BaseHtml::a($text, $url, $options);
    }

    public static function popupSelect($data, $options = [], $text = '选择')
    {
        $options['onclick'] = "javascript:if(window.opener){window.opener.PopupSelect('$data')}window.close();return false;";
        return BaseHtml::a($text, null, $options);
    }
}
