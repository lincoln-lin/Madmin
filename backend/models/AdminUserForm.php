<?php
/**
 * Created by IntelliJ IDEA.
 * User: meizu
 * Date: 2016/5/10
 * Time: 19:04
 */

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

class AdminUserForm extends AdminUser
{
    public $password_old;
    public $password_new2;

    public function scenarios()
    {
        return [
            'user-info' => [
                'phone',
                'remark',
            ],
            'update-password' => [
                'password_old',
                'password',
                'password_new2',
            ],
        ] + parent::scenarios();
    }

    public function rules()
    {
        $rules = [];
        if ($this->isNewRecord) {
            $rules[] = ['password', 'required'];
        }
        $rules[] = [['password', 'password_new2'], 'string', 'min' => 8];
        $rules[] = ['role', 'safe'];
        $rules[] = [['password_old', 'password', 'password_new2'], 'required', 'on' => 'update-password'];
        $rules[] = [['password'], function(){
            if (!Yii::$app->security->validatePassword($this->password_old, $this->getOldAttribute('password_hash'))) {
                $this->addError('password_old', '原密码错误');
            }
            if ($this->password != $this->password_new2) {
                $this->addError('password_new2', '确认密码与新密码不匹配');
            }
            if ($this->password_old == $this->password) {
                $this->addError('password', '新密码与原密码相同');
            }
            $err = '';
            if (!static::isPasswordMatchRule($this->password, $err)) {
                $this->addError('password', $err);
            }
        }, 'on' => 'update-password'];
        return ArrayHelper::merge(
            parent::rules(),
            $rules
        );
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'password' => '密码',
                'password_old' => '原密码',
                'password_new2' => '确认密码',
            ]
        );
    }

}
