<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;

use backend\models\AdminUser;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class HelloController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     */
    public function actionIndex($message = 'hello world')
    {
        echo $message . "\n";
    }

    public function actionLogin($email, $password)
    {
        $user = AdminUser::findOne(['email' => $email]);
        if (!$user) {
            return $this->stdout('用户不存在');
        }
        print_r($user->toArray());
        if (!\Yii::$app->security->validatePassword($password, $user->password_hash)) {
            return $this->stdout('密码不正确', Console::FG_YELLOW);
        }

        return $this->stdout('密码正确', Console::FG_GREEN);
    }

    public function actionRestart()
    {
        // Just for debug; You should know what you are doing.
        if (!Console::confirm('你知道你在干什么吗？')) {
            return false;
        }
        $SQL = <<<SQL
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%user_log}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%migration}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%log}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%key_storage}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%auth_rule}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%auth_item_profile}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%auth_item_child}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%auth_item}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%auth_assignment}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%admin_user}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%admin_menu}};
/* 7:14:20 PM 10.2.67.51 mz-yii2 */ drop table if exists {{%oauth_user}};
SQL;
        foreach (explode("\n", $SQL) as $sql) {
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
            \Yii::$app->db->createCommand($sql)->execute();
            \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();
        }

        system('php yii migrate --migrationPath=@yii/rbac/migrations --interactive=0');
        system('php yii migrate --migrationPath=@yii/log/migrations --interactive=0');
        system('php yii migrate --migrationPath=@backend/migrations --interactive=0');

        \Yii::$app->cache->flush();
    }

    /**
     * 给 RedisProxy 类增加 \Redis 相关方法的注解，方便 IDE 自动完成
     */
    public function actionGenerateRedisProxyMethod()
    {
        require __DIR__.'/Redis.php';
        $relRedis = new \ReflectionClass('\MyRedis');
        $relProxy = new \ReflectionClass('\common\components\redis\RedisProxy');
        $methods = [];
        foreach ($relRedis->getMethods() as $method) {
            if ($method->isPublic() && !$relProxy->hasMethod($method->name)) {
                $methods[] = [
                    'returnType' => $method->getReturnType() ?: 'mixed',
                    'name' => $method->name,
                    'args' => implode(', ', array_map(function(\ReflectionParameter $parameter){
                        $str = '$'.$parameter->name;
                        if ($parameter->isDefaultValueAvailable()) {
                            $str = $str . " = " . str_replace("array (\n", 'array(', var_export($parameter->getDefaultValue(), true));
                        }
                        return $str;
                    }, $method->getParameters()))
                ];
            }
        }
        ob_start();
        foreach ($methods as $method) {
            echo " * @method {$method['returnType']} {$method['name']}({$method['args']})\n";
        }
        $str = ob_get_clean();
        echo $str;
    }

}
