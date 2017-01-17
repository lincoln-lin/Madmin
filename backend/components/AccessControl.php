<?php
namespace backend\components;

use backend\Helper;
use yii\base\ActionFilter;
use yii\helpers\ArrayHelper;
use yii\web\User;
use yii\web\ForbiddenHttpException;
use Yii;

/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/16/16
 * Time: 3:23 PM
 */
class AccessControl extends ActionFilter
{

    public function beforeAction($action)
    {
        $route = '/' . $action->getUniqueId();
        if (Helper::checkRoute($route)) {
            return true;
        } else {
            return $this->denyAccess(Yii::$app->user);
        }
    }

    /**
     * Denies the access of the user.
     * The default implementation will redirect the user to the login page if he is a guest;
     * if the user is already logged, a 403 HTTP exception will be thrown.
     * @param User $user the current user
     * @throws ForbiddenHttpException if the user is already logged in.
     */
    protected function denyAccess($user)
    {
        if ($user->getIsGuest()) {
            $user->loginRequired();
        } else {
            throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action.'));
        }
    }

    protected function isActive($action)
    {
        $uniqueId = $action->getUniqueId();
        if ($uniqueId === Yii::$app->getErrorHandler()->errorAction) {
            return false;
        }

        $user = Yii::$app->user;
        if ($user->getIsGuest() && is_array($user->loginUrl) && isset($user->loginUrl[0]) && $uniqueId === trim($user->loginUrl[0], '/')) {
            return false;
        }

        if ($action->controller->module instanceof \yii\debug\Module or $action->controller->module instanceof \yii\gii\Module) {
            return false;
        }

        return true;
    }
}
