<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_log`.
 */
class m160527_004927_create_user_log extends Migration
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
        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer(),
            'ip' => $this->char(16),
            'action_name' => $this->string(100)->notNull(),
            'action' => $this->string(100)->notNull(),
            'data' => $this->text(),
            'log_time' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->createIndex('idx-uid', '{{%user_log}}', 'uid');
        $this->createIndex('idx-log_time', '{{%user_log}}', 'log_time');
        $this->createIndex('idx-action', '{{%user_log}}', 'action');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_log}}');
    }
}
