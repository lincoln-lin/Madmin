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

use yii\base\Model;
use yii\db\Query;
use yii\rbac\DbManager;
use yii\rbac\Item;
use backend\validators\RbacValidator;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
abstract class AuthItem extends Model
{
    /** @var string */
    public $name;

    /** @var string */
    public $description;

    /** @var string */
    public $rule;

    /** @var string[] */
    public $children = [];

    /** @var \yii\rbac\Role|\yii\rbac\Permission */
    public $item;

    /** @var DbManager */
    protected $manager;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->manager = \Yii::$app->authManager;
        if ($this->item instanceof Item) {
            $this->name        = $this->item->name;
            $this->description = $this->item->description;
            $this->children    = array_keys($this->manager->getChildren($this->item->name));
            if ($this->item->ruleName !== null) {
                $this->rule = get_class($this->manager->getRule($this->item->ruleName));
            }
        }
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'name' => '名称',
            'description' => '描述',
        ];
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'create' => ['name', 'description', 'children', 'rule', 'expire'],
            'update' => ['name', 'description', 'children', 'rule', 'expire'],
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['name', 'required'],
            [['name', 'description', 'rule'], 'trim'],
            ['name', function () {
                if ($this->getItem($this->name) !== null) {
                    $this->addError('name', '已经存在的名称');
                }
            }, 'when' => function () {
                return $this->scenario == 'create' || $this->item->name != $this->name;
            }],
            [['children', 'permissions'], RbacValidator::className()],
            ['rule', function () {
                try {
                    $class = new \ReflectionClass($this->rule);
                } catch (\Exception $ex) {
                    $this->addError('rule', \Yii::t('rbac', 'Class "{0}" does not exist', $this->rule));
                    return;
                }

                if ($class->isInstantiable() == false) {
                    $this->addError('rule', 'TODO'.__FILE__.__LINE__);
                }
                if ($class->isSubclassOf('\yii\rbac\Rule') == false) {
                    $this->addError('rule', 'TODO'.__FILE__.__LINE__);
                }
            }],
        ];
    }
    
    public function getIsNewRecord()
    {
        return $this->item === null;
    }

    /**
     * Saves item.
     *
     * @return bool
     */
    public function save()
    {
        if ($this->validate() == false) {
            return false;
        }

        if ($isNewItem = ($this->item === null)) {
            $this->item = $this->createItem($this->name);
        } else {
            $oldName = $this->item->name;
        }

        $this->item->name        = $this->name;
        $this->item->description = $this->description;

        if (!empty($this->rule)) {
            $rule = \Yii::createObject($this->rule);
            if (null === $this->manager->getRule($rule->name)) {
                $this->manager->add($rule);
            }
            $this->item->ruleName = $rule->name;
        } else {
            $this->item->ruleName = null;
        }

        if ($isNewItem) {
            $this->manager->add($this->item);
        } else {
            $this->manager->update($oldName, $this->item);
        }

        $this->updateChildren();

        $this->afterSave();

        return true;
    }

    public function afterSave()
    {

    }

    /**
     * Updated items children.
     */
    protected function updateChildren()
    {
        $children = $this->manager->getChildren($this->item->name);
        $childrenNames = array_keys($children);

        if (is_array($this->children)) {
            // remove children that
            foreach (array_diff($childrenNames, $this->children) as $item) {
                $this->manager->removeChild($this->item, $children[$item]);
            }
            // add new children
            foreach (array_diff($this->children, $childrenNames) as $item) {
                $this->manager->addChild($this->item, $this->getItem($item));
            }
        } else {
            $this->manager->removeChildren($this->item);
        }
    }

    /**
     * @param  string         $name
     * @return \yii\rbac\Item
     */
    abstract protected function createItem($name);

    public function getItems($type)
    {
        if ($type === Item::TYPE_PERMISSION) {
            return \Yii::$app->authManager->getPermissions();
        } else {
            return \Yii::$app->authManager->getRoles();
        }
    }
    
    public function getItem($name)
    {
        if ($role = \Yii::$app->authManager->getRole($name)) {
            return $role;
        } else {
            return \Yii::$app->authManager->getPermission($name);
        }
    }
}
