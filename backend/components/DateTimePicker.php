<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/26/16
 * Time: 6:25 PM
 */

namespace backend\components;


class DateTimePicker extends \kartik\datetime\DateTimePicker
{
    public $options = [
        'style' => 'width:158px',
    ];
    public $pluginOptions = [
        'autoclose' => true,
        'todayBtn' => true,
    ];
}
