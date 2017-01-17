<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/20/16
 * Time: 3:34 PM
 */

namespace backend\models;


use yii\base\Model;

class RouteForm extends Model
{
    /** @var Route */
    public $route;

    public $menuName;
    public $menuPid;
    public $permissionDesc;

    public function init()
    {
        $this->menuName = $this->route->menu->name;
        $this->menuPid = $this->route->menu->pid;
        $this->permissionDesc = $this->route->permission->description;
    }

    public function attributeLabels()
    {
        return [
            'menuName' => '菜单名称',
            'menuPid' => '父级菜单',
            'permissionDesc' => '权限名称',
        ];
    }

    public function rules()
    {
        return [
            ['menuName', 'trim'],
            ['permissionDesc', 'safe'],
            ['menuPid', function($attr){
                if (!$this->menuName) {return;}

                if ($this->menuPid != -1 &&
                    !(AdminMenu::find()->where(['id' => $this->menuPid])->exists())
                ) {
                    $this->addError($attr, '父菜单不存在');
                }

                if ($this->route->menu->id == $this->menuPid) {
                    $this->addError($attr, '菜单的父菜单不能是自身');
                }
            }],
        ];
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $permission = $this->route->permission;
        $permission->description = $this->permissionDesc;
        \Yii::$app->authManager->update($permission->name, $permission);

        if ($this->menuName) {
            $menu = $this->route->menu;
            $menu->name = $this->menuName;
            $menu->pid = $this->menuPid;
            $menu->url = $this->route->route;
            $menu->save(false);
        }
        return true;
    }
}
