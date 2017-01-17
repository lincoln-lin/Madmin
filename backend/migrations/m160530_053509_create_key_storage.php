<?php

use yii\db\Migration;

/**
 * Handles the creation for table `key_storage`.
 */
class m160530_053509_create_key_storage extends Migration
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
        $this->createTable('{{%key_storage}}', [
            'name' => $this->string(64)->notNull(),
            'value' => $this->text(),
            'PRIMARY KEY (name)',
        ], $tableOptions);

        $this->insert('{{%key_storage}}', [
            'name' => 'AUTH_USER',
            'value' => 'meizu',
        ]);
        $this->insert('{{%key_storage}}', [
            'name' => 'AUTH_PASSWORD',
            'value' => 'meizu2014',
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%key_storage}}');
    }
}
