<?php


namespace backend\components;

use backend\Helper;
use backend\models\AuthItemProfile;
use yii\db\Query;
use yii\rbac\Assignment;
use yii\rbac\Item;
use Yii;
use yii\rbac\Role;

class DbManager extends \yii\rbac\DbManager
{
    protected function checkAccessFromCache($user, $itemName, $params, $assignments)
    {
        if (!isset($this->items[$itemName])) {
            return false;
        }

        $item = $this->items[$itemName];
        if (AuthItemProfile::isItemExpired($item)) {
            return false;
        }

        Yii::trace($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (!$this->executeRule($user, $item, $params)) {
            return false;
        }

        if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
            return true;
        }

        if (!empty($this->parents[$itemName])) {
            foreach ($this->parents[$itemName] as $parent) {
                if ($this->checkAccessFromCache($user, $parent, $params, $assignments)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function checkAccessRecursive($user, $itemName, $params, $assignments)
    {
        if (($item = $this->getItem($itemName)) === null) {
            return false;
        }

        if (AuthItemProfile::isItemExpired($item)) {
            return false;
        }

        Yii::trace($item instanceof Role ? "Checking role: $itemName" : "Checking permission: $itemName", __METHOD__);

        if (!$this->executeRule($user, $item, $params)) {
            return false;
        }

        if (isset($assignments[$itemName]) || in_array($itemName, $this->defaultRoles)) {
            return true;
        }

        $query = new Query;
        $parents = $query->select(['parent'])
            ->from($this->itemChildTable)
            ->where(['child' => $itemName])
            ->column($this->db);
        foreach ($parents as $parent) {
            if ($this->checkAccessRecursive($user, $parent, $params, $assignments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取用户的角色, 包含角色的子角色
     * 注意将此函数与 getRolesByUser 区分开
     * @param $user_id
     * @return array
     */
    public function getAllRolesByUser($user_id)
    {
        $cache = Yii::$app->cache;
        $cacheKey = [__METHOD__, $user_id];
        if ($cache && ($data = $cache->get($cacheKey))) {
            return $data;
        }

        $roles = $this->getRolesByUser($user_id);
        $mergeRoles = [];
        foreach ($roles as $role) {
            if (!AuthItemProfile::isItemExpired($role)) {
                self::_mergeRole($role, $mergeRoles);
            }
        }

        if ($cache) {
            $cache->set($cacheKey, $mergeRoles, Helper::getCacheDuration(), Helper::getTagDependency('rbac'));
        }

        return $mergeRoles;
    }

    private function _mergeRole($role, &$mergeRoles)
    {
        if (isset($mergeRoles[$role->name])) {
            return;
        }
        $mergeRoles[$role->name] = $role;
        $children = $this->getChildren($role->name);
        foreach ($children as $child) {
            if ($child instanceof Role && !AuthItemProfile::isItemExpired($child)) {
                self::_mergeRole($child, $mergeRoles);
            }
        }
    }
}
