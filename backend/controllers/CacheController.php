<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/28/16
 * Time: 11:52 PM
 */

namespace backend\controllers;


use backend\Helper;
use yii\caching\Cache;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;

class CacheController extends BaseController
{

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'flush' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $caches = $this->findCaches();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $caches,
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
    
    public function actionFlush($id)
    {
        $cache = $this->findCaches([$id]);
        if (empty($cache)) {
            Helper::error('缓存不存在');
        } else {
            $cache = array_values($cache)[0];
            /** @var $cache Cache */
            $cache->flush();
            Helper::success("缓存{$id}已全部清除");
        }
        Helper::pageBack();
    }

    private function findCaches(array $cachesNames = [])
    {
        $caches = [];
        $components = Yii::$app->getComponents();
        $findAll = ($cachesNames === []);

        foreach ($components as $name => $component) {
            if (!$findAll && !in_array($name, $cachesNames)) {
                continue;
            }

            if ($component instanceof Cache) {
                $caches[$name] = $component;
            } elseif (is_array($component) && isset($component['class']) && $this->isCacheClass($component['class'])) {
                $caches[$name] = Yii::$app->get($name);
            } elseif (is_string($component) && $this->isCacheClass($component)) {
                $caches[$name] = Yii::$app->get($name);
            }
        }
        
        return $caches;
    }

    /**
     * Checks if given class is a Cache class.
     * @param string $className class name.
     * @return boolean
     */
    private function isCacheClass($className)
    {
        return is_subclass_of($className, Cache::className());
    }
}