<?php

namespace backend\models;

use backend\Helper;
use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;

/**
 * AdminMenuSearch represents the model behind the search form about `backend\models\AdminMenu`.
 */
class AdminMenuSearch extends AdminMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'pid', 'order'], 'integer'],
            [['name', 'url'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ArrayDataProvider
     */
    public function search($params)
    {
        $dataProvider = new ArrayDataProvider([
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $models = $this->getSortedModels();

        foreach ($models as $k => $m) {
            if ($this->name) {
                if (strpos($m->name, $this->name) === false) {
                    unset($models[$k]);
                    continue;
                }
            }
            if ($this->url) {
                if (strpos($m->url, $this->url) === false) {
                    unset($models[$k]);
                    continue;
                }
            }
            if ($this->pid) {
                if (!$this->isChild($m, $this->pid, $this->_allModels)) {
                    unset($models[$k]);
                    continue;
                }
            }
        }

        $dataProvider->allModels = $models;

        return $dataProvider;
    }

    public function map()
    {
        return ArrayHelper::map($this->getSortedModels(), 'id', function($row){
            return str_repeat('　', $row['level'] * 2) . $row['name'];
        });
    }

    protected $_allModels;
    protected function getSortedModels()
    {
        $this->_allModels = $allModels = AdminMenu::find()->orderBy('order desc,id')->indexBy('id')->all();
        $childrenMap = ArrayHelper::index($allModels, null, 'pid');
        $models = [];
        foreach ($allModels as $menu) {
            if ($menu->pid == AdminMenu::ROOT_MENU_ID) {
                $this->mergeModels($models, $menu, $childrenMap);
            }
        }

        return $models;
    }

    protected function mergeModels(&$models, $model, $childrenMap)
    {
        $models[$model->id] = $model;
        if (isset($childrenMap[$model->id])) {
            foreach ($childrenMap[$model->id] as $child) {
                $this->mergeModels($models, $child, $childrenMap);
            }
        }
    }

    /**
     * @param $menu AdminMenu
     * @param $pid
     * @param $menus AdminMenu[]
     * @return bool
     */
    public function isChild($menu, $pid, $menus)
    {
        if ($pid == AdminMenu::ROOT_MENU_ID) return true;

        return
            $menu->id == $pid ||
            $menu->pid == $pid ||
            (isset($menus[$menu->pid]) && $this->isChild($menus[$menu->pid], $pid, $menus))
            ;

    }

    /**
     * 获取登录用户的菜单
     */
    public function getItemsOfLoggedUser()
    {
        list($menus, $childrenMap) = $this->getMenusOfUser(Yii::$app->user->identity);
        foreach ($menus as $menu) {
            $menu['active']= false;

            // 找到当前访问的菜单项
            $route = '/'.trim(Yii::$app->requestedRoute, '/'); // /mz/role
            $r = $action = '/'.Yii::$app->controller->action->getUniqueId(); // /mz/role/index
            if ($route === $menu['url'] || $action === $menu['url']) {
                $menu['active'] = true;
            } else {
                while (($pos = strrpos($r, '/')) > 0) {
                    $r = substr($r, 0, $pos);
                    if ($r === $menu['url']) {
                        $menu['active'] = true;
                        break;
                    }
                }
            }
        }

        foreach ([1, 0] as $level) {
            foreach ($menus as $menu) {

                if ($menu['level'] == $level) {
                    $children = ArrayHelper::getValue($childrenMap, $menu['id'], []);

                    // 当前菜单自己有 URL 属性或者有显示的子菜单，则该菜单可见
                    $menu['visible'] = !empty($menu['url']) || $this->_isVisible($children);
                    $menu['active'] = $menu['active'] || $this->_isActive($children);

                    if ($menu['visible'] && empty($menu['url'])) {
                        foreach ($children as $child) {
                            if ($child['visible']) {
                                $menu['url'] = $child['url'];
                                break;
                            }
                        }
                    }
                }
            }
        }

        // 生成 一二三级 目录结构
        $level1Items = [];
        $level2Items = [];
        $level3Items = [];
        foreach ($childrenMap[self::ROOT_MENU_ID] as $menu1) {
            $level1Items[] = [
                'label' => $menu1['name'],
                'url' => $menu1['is_route'] ? [$menu1['url']] : $menu1['url'],
                'active' => $menu1['active'],
                'visible' => $menu1['visible'],
            ];

            if ($menu1['active']) {
                if (!isset($childrenMap[$menu1['id']])) continue;

                foreach ($childrenMap[$menu1['id']] as $menu2) {
                    $level2Items[] = [
                        'label' => $menu2['name'],
                        'url' => $menu2['is_route'] ? [$menu2['url']] : $menu2['url'],
                        'active' => $menu2['active'],
                        'visible' => $menu2['visible'],
                    ];

                    if ($menu2['active']) {
                        if (!isset($childrenMap[$menu2['id']])) continue;

                        foreach ($childrenMap[$menu2['id']] as $menu3) {
                            $level3Items[] = [
                                'label' => $menu3['name'],
                                'url' => $menu3['is_route'] ? [$menu3['url']] : $menu3['url'],
                                'active' => $menu3['active'],
                                'visible' => $menu3['visible'],
                            ];
                        }
                    }
                }
            }
        }

        $data = [$level1Items, $level2Items, $level3Items];

        return $data;
    }

    protected function getMenusOfUser($user)
    {
        $cache = Yii::$app->cache;
        $cacheKey = [__METHOD__, $user->id];
        if ($cache && $data = $cache->get($cacheKey)) {
            return $data;
        }

        $menus = AdminMenu::find()
            ->orderBy('order desc,id')
            ->indexBy('id')
            ->all();

        /*
         * 一个菜单同时符合以下条件则不显示给用户
         * 1. 是路由菜单
         * 2. 用户没有该权限
         */
        foreach ($menus as $k => $menu) {
            $menu['visible']= true;
            if ($menu['is_route']) {
                if (!Helper::checkRoute($menu['url'], $user)) {
                    unset($menus[$k]);
                }
            }
        }

        $childrenMap = ArrayHelper::index($menus, null, 'pid');

        $data = [$menus, $childrenMap];

        if ($cache) {
            $cache->set($cacheKey, $data, Helper::getCacheDuration(), Helper::getTagDependency('rbac'));
        }

        return $data;
    }

    protected function _isActive($children)
    {
        $active = false;
        foreach ($children as $child) {
            if ($child['active']) {
                $active = true;
                break;
            }
        }
        return $active;
    }

    protected function _isVisible($children)
    {
        $visible = false;
        foreach ($children as $child) {
            if ($child['visible']) {
                $visible = true;
                break;
            }
        }
        return $visible;
    }
}
