<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 2016/9/23
 * Time: 17:12
 */

namespace backend\controllers;


use yii\web\Controller;
use backend\models\KeyStorage;
use yii\web\User;
use yii\web\UserEvent;
use backend\models\AdminUser;
use backend\Helper;

abstract class BaseController extends Controller
{
    /**
     * 控制器需要登录
     * @var bool
     */
    public $needLogin = true;

    /**
     * 控制器里面的Action需要校验权限
     * @var bool
     */
    public $needPermission = true;

    /**
     * 该控制器需要校验 http basic auth
     * @var bool
     */
    public $httpBasicAuth = true;

    /**
     * init() 函数会在 __constructor() 里面被调用
     */
    public function init()
    {
        parent::init();

        // 如果不需要登录，自然也就不需要进行权限校验
        if (!$this->needLogin) {
            $this->needPermission = false;
        }

        // http basic auth 检查
        if ($this->httpBasicAuth) {
            $this->on(self::EVENT_BEFORE_ACTION, function(){
                $req = \Yii::$app->request;
                $res = \Yii::$app->response;
                if (($user = $req->getAuthUser()) && ($pass = $req->getAuthPassword())) {
                    $keyStorage = new KeyStorage();
                    if (($user === $keyStorage->AUTH_USER) && ($pass === $keyStorage->AUTH_PASSWORD)) {
                        return true;
                    }
                }

                $res->getHeaders()->set('WWW-Authenticate', "Basic realm=\"Meizu Admin\"");
                $res->setStatusCode(401);
                \Yii::$app->end();
            });
        }

        // 登录权限检查
        if ($this->needPermission) {
            $this->attachBehavior('accessNeedPermission', 'backend\components\AccessControl');
        } else if ($this->needLogin) {
            $this->attachBehavior('accessNeedLogin', [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ]);
        }

        // 用户登录后，更新用户的最近登录时间
        \Yii::$app->user->on(User::EVENT_AFTER_LOGIN, function ($event) {

            /** @var UserEvent $event */
            /** @var AdminUser $user */
            $user = $event->identity;

            $user->last_login_ip = \Yii::$app->request->getUserIP();
            $user->last_login_time = time();
            $user->save(false);
        });

        // 特定控制器的 POST 访问，使得权限控制相关的缓存失效
        $this->on(self::EVENT_AFTER_ACTION, function () {
            if (\Yii::$app->request->isPost) {
                if (in_array(\Yii::$app->requestedAction->controller->id, [
                    'user',
                    'user-child',
                    'role',
                    'menu',
                ], true)) {
                    Helper::invalidateCache('rbac');
                }
            }
        });
    }
}
