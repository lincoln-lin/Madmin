<?php

use yii\db\Migration;

class m160923_131805_create_user_child extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_child}}', [
            'id' => $this->primaryKey(),
            'uid' => $this->integer()->unsigned(),
            'child_uid' => $this->integer()->unsigned(),
        ], $tableOptions);
        $this->createIndex('idx-uid', '{{%user_child}}', 'uid');
    }

    public function down()
    {
        $this->dropTable('{{%user_child}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
