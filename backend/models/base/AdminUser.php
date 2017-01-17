<?php

namespace backend\models\base;

use Yii;

/**
 * This is the model class for table "{{%admin_user}}".
 *
 * @property integer $id
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $phone
 * @property string $realname
 * @property string $last_login_ip
 * @property integer $last_login_time
 * @property string $remark
 * @property integer $unlock_time
 * @property integer $login_error_num
 * @property string $title
 * @property string $employee_id
 * @property string $last_department
 */
class AdminUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['auth_key', 'password_hash', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_time', 'unlock_time', 'login_error_num'], 'integer'],
            [['remark', 'last_department'], 'string'],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 18],
            [['realname', 'title', 'employee_id'], 'string', 'max' => 64],
            [['last_login_ip'], 'string', 'max' => 20],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'phone' => '手机号',
            'realname' => '真实姓名',
            'last_login_ip' => 'Last Login Ip',
            'last_login_time' => 'Last Login Time',
            'remark' => 'Remark',
            'unlock_time' => '帐号解锁时间',
            'login_error_num' => 'Login Error Num',
            'title' => '职位',
            'employee_id' => '工号',
            'last_department' => 'Last Department',
        ];
    }
}
