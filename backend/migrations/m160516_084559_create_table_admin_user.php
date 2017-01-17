<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_admin_user`.
 */
class m160516_084559_create_table_admin_user extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%admin_user}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'phone' => $this->string(18)->comment('手机号'),
            'realname' => $this->string(64)->comment('真实姓名'),
            'last_login_ip' => $this->string(20),
            'last_login_time' => $this->integer(),
            'remark' => $this->text(),
            'unlock_time' => $this->integer()->comment('帐号解锁时间'),
            'login_error_num' => $this->integer(),
            'title' => $this->string(64)->comment('职位'),
            'employee_id' => $this->string(64)->comment('工号'),
            'last_department' => $this->text()->comment('上一次从 uac 获取到的部门数据'),
        ], $tableOptions);

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%admin_user}}');
    }
}
