<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 9:46 AM
 */

namespace backend\models;

use backend\models\base\AdminMenu as BaseAdminMenu;
use yii\helpers\ArrayHelper;

class AdminMenu extends BaseAdminMenu
{
    public $active;
    public $visible;

    const ROOT_MENU_ID = -1;

    public $_message_;

    public function getParent()
    {
        return $this->hasOne(static::className(), ['id' => 'pid']);
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['pid', function($attr){
                if ($this->pid != -1 &&
                    !(AdminMenu::find()->where(['id' => $this->pid])->exists())
                ) {
                    $this->addError($attr, '父菜单不存在');
                }

                if ($this->id == $this->pid) {
                    $this->addError($attr, '菜单的父菜单不能是自身');
                }
            }],
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'name' => '菜单名称',
            'pid' => '父级菜单',
            'url' => 'Url',
            'order' => '排序',
        ]);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (static::find()->where(['pid' => $this->id])->exists()) {
                $this->_message_ = '存在子菜单,禁止删除';
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public function getRootParents()
    {
        $rows = static::find()->select('id,name')
            ->where(['pid' => self::ROOT_MENU_ID])
            ->orderBy('order desc,id')
            ->asArray()
            ->all();
        return ArrayHelper::map($rows, 'id', 'name');
    }

    public function beforeSave($insert)
    {
        if ( parent::beforeSave($insert) ) {
            $this->level = $this->parent ? $this->parent->level + 1 : 0;
            return true;
        }
        return false;
    }

    public function getIs_route()
    {
        return isset($this->url[0]) && $this->url[0] === '/';
    }

}
