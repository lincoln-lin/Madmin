<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/26/16
 * Time: 2:18 PM
 */

namespace backend\models;

use backend\models\base\Log as BaseLog;

class Log extends BaseLog
{
    public function attributeLabels()
    {
        return [
            'level' => '错误级别',
            'log_time' => '时间',
            'message' => '内容',
        ];
    }
    
    public $_userLog;
    public function getUserLogData($field)
    {
        if (null === $this->_userLog) {
            $this->_userLog = json_decode($this->message, true);
        }
        return isset($this->_userLog[$field]) ? $this->_userLog[$field] : '';
    }
}