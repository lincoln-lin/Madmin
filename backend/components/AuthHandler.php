<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/1/16
 * Time: 15:13
 */

namespace backend\components;

use backend\models\OauthUser as Auth;
use backend\models\AdminUser as User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'email');
        $id = ArrayHelper::getValue($attributes, 'id');

        /* @var Auth $auth */
        $auth = Auth::find()->where([
            'source' => $this->client->getId(),
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                /* @var User $user */
                $user = $auth->user;
                $this->updateUserInfo($user);
                Yii::$app->user->login($user, ArrayHelper::getValue(Yii::$app->params, 'mz.admin.user.rememberMeDuration'));
            } else { // signup
                //@todo 如果邮箱已经存在,那么直接绑定, 该行为表现待与产品沟通
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    $user = User::findOne(['email' => $email]); //
                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'email' => $email,
                        'password' => $password,
                        'title' => ArrayHelper::getValue($attributes, 'title'),
                        'employee_id' => ArrayHelper::getValue($attributes, 'employee_id'),
                        'realname' => ArrayHelper::getValue($attributes, 'realname'),
                        'phone' => ArrayHelper::getValue($attributes, 'phone'),
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();
                    $user->save();
                }
                $auth = new Auth([
                    'user_id' => $user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$id,
                ]);
                if ($auth->save()) {
                    $this->updateUserRole($user, ArrayHelper::getValue($attributes, 'department'));
                    Yii::$app->user->login($user, ArrayHelper::getValue(Yii::$app->params, 'mz.admin.user.rememberMeDuration'));
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to save {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    $user = $auth->user;
                    $this->updateUserInfo($user);
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }

    /**
     * @param User $user
     */
    private function updateUserInfo(User $user)
    {
        $attributes = $this->client->getUserAttributes();
        foreach (['email', 'title', 'employee_id', 'realname', 'phone'] as $column) {
            if ($value = ArrayHelper::getValue($attributes, $column)) {
                $user->{$column} = $value;
            }
        }
        $user->save();

        $this->updateUserRole($user, ArrayHelper::getValue($attributes, 'department'));
    }

    public function updateUserRole(User $user, $department)
    {
        // $department 和 $user->last_department 进行比较, 如果数据一致则不更新用户角色数据
        $department = $this->formatDepartment($department);
        sort($department);

        $encoded = \json_encode($department);
        if ($encoded == $user->last_department) {
            return;
        }

        $old = (array)\json_decode($user->last_department, true);
        $shouldDelete = array_diff($old, $department);
        $shouldAdd = array_diff($department, $old);
        $am = Yii::$app->authManager;
        foreach ((array)$shouldAdd as $item) {
            $role = $am->getRole($item);
            if ($role === null) {
                $role = $am->createRole($item);
                $am->add($role);
            }
            $am->assign($role, $user->id);
        }
        foreach ((array)$shouldDelete as $item) {
            if ($role = $am->getRole($item)) {
                $am->revoke($role, $user->id);
            }
        }

        $user->last_department = $encoded;
        $user->save();
    }

    private function formatDepartment($department)
    {
        return array_filter(explode(',', $department));
    }
}
