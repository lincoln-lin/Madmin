<?php

use yii\db\Migration;

/**
 * Handles the creation for table `auth_item_profile`.
 */
class m160721_085253_create_auth_item_profile extends Migration
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

        $this->createTable('{{%auth_item_profile}}', [
            'item_name' => $this->string(64),
            'expire' => $this->integer()->defaultValue(0)->notNull(),
            'PRIMARY KEY (item_name)',
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%auth_item_profile}}');
    }
}
