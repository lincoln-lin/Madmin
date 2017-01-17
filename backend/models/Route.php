<?php

namespace backend\models;

use backend\controllers\BaseController;
use yii\helpers\ArrayHelper;
use backend\Helper;
use Yii;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\data\ArrayDataProvider;
use yii\helpers\VarDumper;
use Exception;
use yii\rbac\Permission;

/**
 * Description of Route
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 *
 * @property AdminMenu $menu
 * @property Permission $permission
 */
class Route extends \yii\base\Model
{
    /** @var  string 路由 比如 /user/login */
    public $route;

    protected $_menu;
    public function getMenu()
    {
        if (!$this->_menu) {
            $this->_menu = isset(static::$_menus[$this->route]) ? static::$_menus[$this->route]
                :
                new AdminMenu();
        }
        return $this->_menu;
    }

    protected $_permission;
    public function getPermission()
    {
        if (!$this->_permission) {
            if (isset(static::$_permissions[$this->route])) {
                $this->_permission = static::$_permissions[$this->route];
            } else {
                $this->_permission = Yii::$app->authManager->createPermission($this->route);
                Yii::$app->authManager->add($this->_permission);
            }
        }
        return $this->_permission;
    }

    /**
     * Get all routes
     * @return array
     */
    protected static $_menus;
    protected static $_permissions;
    public function search($onlyHasDescription = false)
    {
        $routes = $this->getRoutes();
        static::$_menus = AdminMenu::find()->indexBy('url')->all();
        foreach (static::$_menus as $url => $menu) {
            if (!$menu['is_route']) {
                unset(static::$_menus[$url]);
            }
        }
        foreach (Yii::$app->authManager->getPermissions() as $permission) {
            if ($permission->name[0] === '/') {
                static::$_permissions[$permission->name] = $permission;
            }
        }

        $models = [];
        foreach ($routes as $route) {
            $model = new static(['route' => $route]);
            if (!$onlyHasDescription || $model->permission->description) {
                $models[] = $model;
            }
        }

        $dataProvider = new ArrayDataProvider([
            'key' => 'route',
            'allModels' => $models,
            'pagination' => false,
        ]);

        return $dataProvider;
    }

    public function searchCheckable()
    {
        return $this->search();
    }

    public function getLevel()
    {
        $str = rtrim($this->route, '/');
        $level =  count(explode('/', $str)) - 2;
        if ($level < 0) {
            $level = 0;
        }

        return $level;
    }

    public function attributeLabels()
    {
        return [
            'route' => '路由',
            'menu.name' => '菜单名称',
            'permission.description' => '权限名称',
        ];
    }

    /**
     * @param $route string
     * @return static|null
     */
    public function findOne($route)
    {
        $dataProvider = $this->search();
        foreach ($dataProvider->allModels as $model) {
            if ($model->route === $route) {
                return $model;
            }
        }
    }

    public function getRoutes()
    {
        $routes = $this->getAppRoutes();
        foreach ($routes as $k => $route) {
            if (Helper::isAllowAction($route)) {
                unset($routes[$k]);
            }
        }

        return $routes;
    }

    /**
     * Get list of application routes
     * @return array
     */
    public function getAppRoutes($module = null)
    {
        $oldFormat = Yii::$app->response->format;
        if ($module === null) {
            $module = Yii::$app;
        } elseif (is_string($module)) {
            $module = Yii::$app->getModule($module);
        }
        $this->getRouteRecrusive($module, $result);
        Yii::$app->response->format = $oldFormat;
        return $result;
    }

    /**
     * Get route(s) recrusive
     * @param \yii\base\Module $module
     * @param array $result
     */
    protected function getRouteRecrusive($module, &$result)
    {
        if ($module instanceof \yii\gii\Module || $module instanceof \yii\debug\Module) {
            return;
        }

        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $all = '/' . ltrim($module->uniqueId, '/');
            $result[$all] = $all;
            foreach ($module->getModules() as $id => $_) {
                $child = $module->getModule($id);
                if ($child !== null && $child instanceof Module) {
                    $this->getRouteRecrusive($child, $result);
                }
            }

            foreach ($module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }

            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            $this->getControllerFiles($module, $namespace, '', $result);
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    protected function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file) && preg_match('%^[a-z0-9_/]+$%i', $file . '/')) {
                    $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $baseName = substr(basename($file), 0, -14);
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', $baseName));
                    $id = ltrim(str_replace(' ', '-', $name), '-');
                    $className = $namespace . $baseName . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        $this->getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list action of controller
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    protected function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $reflection = new \ReflectionClass(is_string($type) ? $type : $type['class']);
            if (!$reflection->isInstantiable()) {
                return;
            }
            /* @var $controller \backend\controllers\BaseController */
            $controller = Yii::createObject($type, [$id, $module]);
            if (!$controller instanceof BaseController || !$controller->needPermission) {
                return;
            }
            $all = "/{$controller->uniqueId}";
            $result[$all] = $all;
            $this->getActionRoutes($controller, $result);
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    protected function getActionRoutes($controller, &$result)
    {
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                $result[$prefix . $id] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $id = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                    $result[$id] = $id;
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Set default rule of parameterize route.
     */
    protected function setDefaultRule()
    {
        if (Yii::$app->getAuthManager()->getRule(RouteRule::RULE_NAME) === null) {
            Yii::$app->getAuthManager()->add(new RouteRule());
        }
    }
}
