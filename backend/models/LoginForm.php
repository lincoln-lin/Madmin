<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/19/16
 * Time: 3:28 PM
 */

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends Model
{

    public $verifyCode;

    /** @var string User's email or username */
    public $login;

    /** @var string User's plain password */
    public $password;

    /** @var string Whether to remember the user */
    public $rememberMe = false;

    /** @var  AdminUser */
    protected $user;

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'login'      => '邮箱',
            'password'   => '密码',
            'rememberMe' => '记住登录',
        ];
    }

    /** @inheritdoc */
    public function rules()
    {
        return [
            ['verifyCode', 'captcha', 'captchaAction' => 'security/captcha'],
            'requiredFields' => [['login', 'password'], 'required'],
            'loginTrim' => ['login', 'trim'],
            ['login', function($attribute){
                if (($user = $this->user) === null) {
                    $this->addError($attribute, '账号不存在');
                    return;
                }

                if (!$user->getIsActive()) {
                    $this->addError($attribute, '该账号被禁用');
                    return;
                }

                if ($user->unlock_time > time()) {
                    $this->addError($attribute, '该账号被锁定, 请稍后重试');
                    return;
                }
            }],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate() && $this->validatePassword()) {
            return Yii::$app->getUser()->login($this->user, $this->rememberMe ? ArrayHelper::getValue(Yii::$app->params, 'mz.admin.user.rememberMeDuration') : 0);
        } else {
            return false;
        }
    }

    public function validatePassword()
    {
        if ($user = $this->user) {
            if (!Yii::$app->security->validatePassword($this->password, $this->user->password_hash)) {
                $this->addError('password', '密码错误');
                $user->login_error_num ++;
                if ($user->login_error_num >= 10) {
                    $user->unlock_time = time() + 7200 * 2;
                } else if ($user->login_error_num >= 5) {
                    $user->unlock_time = time() + 7200;
                }
                $user->save(false);
            } else {
                $user->login_error_num = 0;
                $user->save(false);
                return true;
            }
        }
        return false;
    }

    /** @inheritdoc */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = AdminUser::findOne(['email' => trim($this->login)]);
            return true;
        }

        return false;
    }
}
