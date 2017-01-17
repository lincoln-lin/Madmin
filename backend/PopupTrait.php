<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 5:08 PM
 */

namespace backend;

trait PopupTrait
{
    protected function closeWindow($msg = '', $reload = true)
    {

        Helper::closeWindow($msg, $reload);

    }
}
