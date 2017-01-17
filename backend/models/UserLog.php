<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/27/16
 * Time: 10:15 AM
 */

namespace backend\models;


class UserLog extends \backend\models\base\UserLog
{
    public function getUser()
    {
        return $this->hasOne(AdminUser::className(), ['id' => 'uid']);
    }

    public function attributeLabels()
    {
        return [
            'uid' => '操作人',
            'action' => '动作',
            'action_name' => '标题',
            'data' => '数据',
        ];
    }
}