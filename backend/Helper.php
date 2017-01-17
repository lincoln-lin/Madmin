<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/16/16
 * Time: 3:29 PM
 */

namespace backend;

use backend\models\AdminUser;
use backend\models\UserLog;
use Yii;
use yii\console\Application;
use yii\helpers\ArrayHelper;
use yii\web\User;
use yii\caching\TagDependency;

class Helper
{
    public static function getCurrentUserId()
    {
        try {
            return Yii::$app->user->id;
        } catch (\Exception $e) {
            return 0;
        }
    }

    public static function lastElement($array)
    {
        $tmp = array_slice($array, -1);
        return array_pop($tmp);
    }

    /**
     * 当前用户是否是具有 Root 权限的用户
     * @return bool
     */
    public static function isRootUser()
    {
        return static::checkRoute('/');
    }

    private static $_checkRoutes;
    /**
     * @param string $r
     * @param AdminUser|null $user
     * @return bool
     */
    public static function checkRoute($r, $user = null)
    {
        if (static::isAllowAction($r)) {
            return true;
        }

        if ($user === null) {
            if (Yii::$app instanceof Application) {
                return true;
            }
            if (Yii::$app->user->isGuest) {
                return false;
            }
            $user = Yii::$app->user->identity;
        }

        if (isset(static::$_checkRoutes[$user->id][$r])) {
            return static::$_checkRoutes[$user->id][$r];
        }

        $cache = Yii::$app->cache;
        $cacheKey = [__METHOD__, $user->id, $r];
        if ($cache && ($result = $cache->get($cacheKey))) {
            $result --;
        } else {
            $result = static::_checkRouteRecursive($r, $user); // false or true
            $cache->set($cacheKey, $result + 1, Helper::getCacheDuration(), Helper::getTagDependency('rbac')); // $result + 1 = 1 or 2
        }

        static::$_checkRoutes[$user->id][$r] = $result;

        return $result;
    }

    /**
     * @param $r string
     * @param $user AdminUser
     * @return boolean
     */
    protected static function _checkRoute($r, $user)
    {

        $authManager = Yii::$app->authManager;

        if ($authManager->checkAccess($user->id, '/')) {
            return true;
        }
        if ($authManager->checkAccess($user->id, $r)) {
            return true;
        }
        while (($pos = strrpos($r, '/')) > 0) {
            $r = substr($r, 0, $pos);
            if ($authManager->checkAccess($user->id, $r)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $r string
     * @param $user AdminUser
     * @return bool
     */
    protected static function _checkRouteRecursive($r, $user)
    {
        if (static::_checkRoute($r, $user)) {
            // 本身拥有权限,并且主账户也拥有该权限
            if ($user->parent) {
                if ($user->parent->getIsAvailable()) {
                    return static::_checkRoute($r, $user->parent->id);
                }
            } else {
                return true;
            }
        }

        return false;
    }

    public static function isAllowAction($r)
    {
        $allowActions = ArrayHelper::getValue(Yii::$app->params, 'mz.admin.allowActions', []);
        if (in_array($r, $allowActions)) {
            return true;
        }
        while (($pos = strrpos($r, '/')) > 0) {
            $r = substr($r, 0, $pos);
            if (in_array($r, $allowActions)) {
                return true;
            }
        }

        return false;
    }

    public static function unparse_url($parsed_url)
    {
        $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }

    public static function success($msg = '')
    {
        if ($msg === '') {
            $msg = '操作成功';
        }
        \Yii::$app->session->setFlash('_success_', $msg);
    }

    public static function error($msg = '')
    {
        if ($msg === '') {
            $msg = '操作失败';
        }
        \Yii::$app->session->setFlash('_danger_', $msg);
    }

    public static function warning($msg)
    {
        \Yii::$app->session->setFlash('_warning_', $msg);
    }

    public static function pageBack()
    {
        \Yii::$app->response->redirect($_SERVER['HTTP_REFERER']);
    }

    public static function successBack($msg = '')
    {
        static::success($msg);
        static::pageBack();
    }

    public static function closeWindow($msg = '', $reload = true)
    {
        if ($msg === '') {
            $msg = '操作成功';
        }
        if ($msg) {
            \Yii::$app->session->setFlash('_success_', $msg);
        }

        echo '<script>';
        if ($reload) {
            echo 'if (window.opener) { window.opener.location.reload(); }';
        }
        echo "if (window.opener) { window.close(); } else { window.location='{$_SERVER['HTTP_REFERER']}' }";
        echo '</script>';

        \Yii::$app->end();
    }

    public static function getTagDependency($tagSuffix='')
    {
        return new TagDependency([
            'tags' => static::getCacheTag($tagSuffix),
        ]);
    }

    public static function getCacheTag($tagSuffix='')
    {
        return ArrayHelper::getValue(Yii::$app->params, 'mz.admin.cacheTag', '_mz.admin.cache.tag_') . '.' . $tagSuffix;
    }

    public static function getCacheDuration()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'mz.admin.cacheDuration', 864000);
    }

    public static function invalidateCache($tagSuffix='')
    {
        if ($cache = Yii::$app->cache) {
            TagDependency::invalidate($cache, static::getCacheTag($tagSuffix));
        }
    }

    /**
     * 记录用户操作日志
     * @param $actionName string
     * @param $data string|array
     */
    public static function userLog($actionName, $data = null)
    {
        if (null === $data) {
            if (Yii::$app->request->isPost) {
                $data = ['post' => Yii::$app->request->post(), 'get' => Yii::$app->request->get()];
            } else {
                $data = Yii::$app->request->get();
            }
        }
        $attrs = [
            'ip' => Yii::$app->request->userIP,
            'uid' => Yii::$app->user->id ?: 0,
            'action' => Yii::$app->controller->action->uniqueId,
            'action_name' => $actionName,
            'data' => is_scalar($data) ? $data : json_encode($data, JSON_UNESCAPED_UNICODE),
            'log_time' => time(),
        ];
        (new UserLog($attrs))->save(false);
    }

    //@todo
    public static function uploadCsv()
    {

    }

    public static function downloadCsv($models, $headerMap, $filename = null, $values = [])
    {
        $cols = array_keys($headerMap);
        $f = fopen('php://memory', 'w');
        fputcsv($f, static::convertEncode(array_values($headerMap), 'gbk', 'utf-8'));
        foreach ($models as $model) {
            if (is_array($model) || is_object($model)) {
                $data = [];
                foreach ($cols as $col) {
                    if (isset($values[$col])) {
                        $data[] = $values[$col]($model);
                    } else {
                        $data[] = ArrayHelper::getValue($model, $col);
                    }
                }
            } else {
                throw new \RuntimeException('数据必须是数组或者对象');
            }
            fputcsv($f, static::convertEncode($data, 'gbk', 'utf-8'));
        }
        if ($filename === null)
        {
            $filename = date('Y-m-d_His').'.csv';
        }
        return Yii::$app->response->sendStreamAsFile($f, $filename, ['mimeType' => 'text/csv']);
    }

    public static function convertEncode($data, $to, $from)
    {
        if (is_array($data)) {
            return array_map(function($data)use($to, $from){
                return mb_convert_encoding($data, $to, $from);
            }, $data);
        } else {
            return mb_convert_encoding($data, $to, $from);
        }
    }

    /**
     * @param $table string 表名
     * @param $rows array 关联数组
     * @param array $merge 关联数组
     * @param string $row_column 如果 $rows 不是二维数组,那么这个参数指定 rows 的字段名
     * @return bool
     */
    public static function batchInsert($table, $rows, $merge = [], $row_column = null)
    {
        if (empty($rows)) {
            return true;
        }
        $values = [];
        foreach ($rows as $row) {
            if (!is_array($row)) {
                $row = [$row_column => $row];
            }
            $map = array_merge($merge, $row);
            $values[] = array_values($map);
        }
        $columns = array_keys($map);
        return Yii::$app->db->createCommand()->batchInsert($table, $columns, $values)->execute();
    }
}
