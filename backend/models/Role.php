<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace backend\models;

use backend\Helper;
use backend\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Role extends AuthItem
{

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['expire', 'safe', 'on' => ['create', 'update']]
        ]);
    }

    /** @inheritdoc */
    public function getRoles()
    {
        $map = array_filter(ArrayHelper::map($this->getItems(Item::TYPE_ROLE), 'name', 'name'), function($name){
            if ('!!!' === substr($name, 0, 3)) {
                return false;
            } else {
                return true;
            }
        });
        foreach ($map as $k => $name) {
            if ($this->item && $name == $this->item->name) {
                unset($map[$k]);
            }
        }
        return $map;
    }

    /** @inheritdoc */
    protected function createItem($name)
    {
        return $this->manager->createRole($name);
    }

    public function attributeLabels()
    {
        return array_merge( parent::attributeLabels(), [
            'children' => '包含子用户组权限',
            'expire' => '过期时间',
        ]);
    }

    public function beforeValidate()
    {
        if (!$this->getIsNewRecord()) {
            $this->children = array_filter(array_values(array_merge((array)$this->children, $this->getPermissions())));
            if (empty($this->children)) {
                $this->children = null;
            }
        }
        return parent::beforeValidate();
    }

    /** @var  string[]  用户组包含的权限名称 */
    protected $_permissions;
    public function getPermissions()
    {
        if ($this->_permissions === null) {
            $children = $this->manager->getChildren($this->item->name);
            $this->_permissions = [];
            foreach ($children as $item) {
                if ($item instanceof \yii\rbac\Permission) {
                    $this->_permissions[] = $item->name;
                }
            }
        }

        return $this->_permissions;
    }

    public function setPermissions($permissions)
    {
        $permissions = $permissions ?: [];
        $children = $this->getPermissions();
        foreach (array_diff($children, $permissions) as $item) {
            $this->manager->removeChild($this->item, $this->getItem($item));
        }
        foreach (array_diff($permissions, $children) as $item) {
            $this->manager->addChild($this->item, $this->getItem($item));
        }

        return true;
    }

    protected $_profile;
    public function getProfile()
    {
        if (!$this->_profile) {
            $this->_profile = AuthItemProfile::findOne($this->name) ?:
                new AuthItemProfile([
                    'item_name' => $this->name
                ]);
        }

        return $this->_profile;
    }

    public function getExpire()
    {
        return $this->getProfile()->expire > 0 ? date('Y-m-d H:i', $this->getProfile()->expire) : '';
    }

    public function setExpire($expire)
    {
        $this->getProfile()->expire = (int)strtotime($expire);
    }

    public function afterSave()
    {
        $this->getProfile()->save();
    }
}
