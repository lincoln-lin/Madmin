<?php

use yii\db\Migration;

/**
 * Handles the creation for table `table_menu`.
 */
class m160520_013839_create_table_menu extends Migration
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
        $this->createTable('{{%admin_menu}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'pid' => $this->integer(),
            'url' => $this->string(),
            'order' => $this->integer(),
            'level' => $this->integer(),
        ], $tableOptions);

        $sql = <<<'SQL'
INSERT INTO {{%admin_menu}} (`id`, `name`, `pid`, `url`, `order`, `level`)
VALUES
	(1, '系统管理', -1, '', 5, 0),
	(5, '系统管理', 1, '', 70, 1),
	(6, '用户', 5, '/user/index', 40, 2),
	(7, '路由权限', 5, '/route/index', 20, 2),
	(29, '用户组', 5, '/role/index', 30, 2),
	(34, '菜单', 5, '/menu/index', 10, 2),
	(40, '系统日志', 5, '/log/index', 5, 2),
	(41, '操作日志', 5, '/user-log/index', 6, 2),
	(42, 'SQL查询', 5, '/sql/query', NULL, 2),
	(43, '缓存', 5, '/cache/index', 4, 2),
	(44, '系统参数', 5, '/system/index', NULL, 2);

SQL;
        Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%admin_menu}}');
    }
}
