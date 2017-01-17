<?php

use yii\db\Migration;

/**
 * Handles the creation for table `auth`.
 */
class m160801_072710_create_oauth_user extends Migration
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
        $this->createTable('{{%oauth_user}}', [
            'source' => $this->string(128)->notNull(),
            'source_id' => $this->string(128)->notNull(),
            'user_id' => $this->integer(),
            'PRIMARY KEY (source, source_id)'
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%oauth_user}}');
    }
}
