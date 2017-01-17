<?php

namespace backend\models\base;

use Yii;

/**
 * This is the model class for table "{{%user_log}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $action_name
 * @property string $action
 * @property string $data
 * @property integer $log_time
 */
class UserLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'log_time'], 'integer'],
            [['action_name', 'action', 'log_time'], 'required'],
            [['data'], 'string'],
            [['ip'], 'string', 'max' => 16],
            [['action_name', 'action'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => 'Uid',
            'ip' => 'Ip',
            'action_name' => 'Action Name',
            'action' => 'Action',
            'data' => 'Data',
            'log_time' => 'Log Time',
        ];
    }
}
