<?php

namespace backend\controllers;

use backend\AjaxValidationTrait;
use backend\Helper;
use backend\models\AdminUser;
use Yii;
use backend\models\LoginForm;
use yii\authclient\AuthAction;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\components\AuthHandler;
use backend\models\ResetPasswordForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;

class SecurityController extends BaseController
{
    
    use AjaxValidationTrait;

    public $needLogin = false;

    public $layout = 'minimal';

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => (YII_ENV_DEV || YII_ENV_TEST) ? 'Test' : null,
                'width' => 90,
                'minLength' => 4,
                'maxLength' => 5,
            ],
            'auth' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        $this->performAjaxValidation($model);

        if ($model->load(Yii::$app->getRequest()->post()) && $model->login()) {
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect('login');
    }

    public function actionRequestPasswordReset()
    {
        Yii::$app->response->format = 'raw';
        $user = AdminUser::findOne(['email' => Yii::$app->request->post('email')]);
        if (!$user) {
            return '用户不存在';
        }
        $user->sendResetPasswordEmail();
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Helper::success('密码设置成功');
            return $this->goHome();
        }
        return $this->render('reset-password', [
            'model' => $model,
        ]);
    }
}
