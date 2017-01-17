<?php

use backend\models\AdminUser;
use yii\db\Migration;

class m160801_094526_auth_init extends Migration
{

    public $sql;

    public function init()
    {
        parent::init();

        $am = Yii::$app->authManager;
        $this->sql= <<<SQL
INSERT INTO {$am->itemTable} (`name`, `type`, `description`, `rule_name`, `data`, `created_at`, `updated_at`)
VALUES
	('/cache', 2, '清空缓存', NULL, NULL, 1464488260, 1464488279),
	('/log/index', 2, '查看系统日志', NULL, NULL, 1464246844, 1464251334),
	('/log/user-log', 2, '查看操作日志', NULL, NULL, 1464251252, 1464251323),
	('/menu', 2, '管理菜单', NULL, NULL, 1463993874, 1464075881),
	('/menu/create', 2, '添加菜单', NULL, NULL, 1463993874, 1464075904),
	('/menu/delete', 2, '删除菜单', NULL, NULL, 1463993874, 1464075923),
	('/menu/index', 2, '菜单列表', NULL, NULL, 1463993874, 1464075896),
	('/menu/update', 2, '编辑菜单', NULL, NULL, 1463993874, 1464075915),
	('/role', 2, '管理用户组', NULL, NULL, 1464160473, 1464160533),
	('/role/create', 2, '添加用户组', NULL, NULL, 1464073397, 1464076256),
	('/role/delete', 2, '删除用户组', NULL, NULL, 1464073397, 1464076269),
	('/role/index', 2, '用户组列表', NULL, NULL, 1464073397, 1464076249),
	('/role/permission', 2, '设置用户组权限', NULL, NULL, 1464078289, 1464085824),
	('/role/update', 2, '编辑用户组', NULL, NULL, 1464073397, 1464076262),
	('/route', 2, '路由权限', NULL, NULL, 1463925769, 1464054847),
	('/sql/query', 2, 'SQL查询', NULL, NULL, 1464318896, 1464318916),
	('/user', 2, '管理用户', NULL, NULL, 1463925769, 1464054601),
	('/user-log/index', 2, '查看操作日志', NULL, NULL, 1464315652, 1464316072),
	('/user/create', 2, '添加用户', NULL, NULL, 1463925769, 1464074997),
	('/user/index', 2, '用户列表', NULL, NULL, 1463925769, 1464074517),
	('/user/update', 2, '编辑用户', NULL, NULL, 1463925769, 1464075006);
SQL;
    }

    public function up()
    {
        $user = new \backend\models\AdminUser(
            [
                'email' => 'admin@meizu.com',
                'password' => 'asdfqwWERxx',
                'realname' => '开发帐号',
                'phone' => '12345678901',
            ]
        );
        $user->save();

        $am = Yii::$app->authManager;
        $rootPermission = $am->createPermission('/');
        $rootPermission->description = 'root access【访问全部路由的权限】';
        $am->add($rootPermission);

        $rootRole = $am->createRole('超级管理员');
        $rootRole->description = 'root access';
        $am->add($rootRole);

        $am->addChild($rootRole, $rootPermission);

        $am->assign($rootRole, $user->id);

        $sql = $this->sql;
        Yii::$app->db->createCommand($sql)->execute();
    }

    public function down()
    {
        $am = Yii::$app->authManager;
        AdminUser::findOne(['email' => 'admin@meizu.com'])->delete();
        $am->removeAll();

        return true;
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
