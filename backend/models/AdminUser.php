<?php

namespace backend\models;

use backend\Helper;
use Yii;
use yii\db\Exception;
use yii\behaviors\TimestampBehavior;
use yii\caching\TagDependency;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\rbac\DbManager;
use yii\rbac\Item;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use backend\models\base\AdminUser as BaseAdminUser;

/**
 * Class AdminUser
 * @package backend\models
 *
 * @property AdminUser $parent
 */
class AdminUser extends BaseAdminUser implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_FORBIDDEN = 20;

    /** @var  int  主账号ID */
    public $parentUid;

    public static function status()
    {
        return [
            self::STATUS_ACTIVE => '可用',
            self::STATUS_FORBIDDEN => '禁用',
            self::STATUS_DELETED => '作废',
        ];
    }

    public function getStatusLabel()
    {
        $map = static::status();
        switch ($this->status) {
            case self::STATUS_ACTIVE:
                $class = 'success';
                break;
            case self::STATUS_FORBIDDEN:
                $class = 'warning';
                break;
            case self::STATUS_DELETED:
                $class = 'danger';
                break;
        }
        return "<span class='label label-{$class}'>{$map[$this->status]}</span>";
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_FORBIDDEN, self::STATUS_DELETED]],
        ]);
    }

    public function init()
    {
        if (null === $this->status) {
            $this->status = self::STATUS_ACTIVE;
        }
        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'phone' => '手机号',
            'realname' => '真实姓名',
            'last_login_ip' => '最近登录 IP',
            'last_login_time' => '最近登录时间',
            'remark' => '备注',
            'unlock_time' => '帐号解锁时间',
            'login_error_num' => 'Login Error Num',
            'role' => '用户组',
            'roleDesc' => '用户组',
        ] + parent::attributeLabels();
    }

    public static function findIdentity($id)
    {
        return Yii::$app->db->cache(function ($db) use ($id) {
            return static::find()->where(['id' => $id])
                ->andWhere(['not in', 'status', [self::STATUS_DELETED]])
                ->one();
        }, null, new TagDependency(['tags' => 'AdminUser'.$id]));
    }

    /**
     * 只唯一包含该用户的角色组
     * @return string
     */
    public function getSelfRoleName()
    {
        return '!!!'.$this->id;
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert && $this->parentUid) {
            Yii::$app->db->createCommand()->insert('{{%user_child}}', [
                'uid' => $this->parentUid,
                'child_uid' => $this->id
            ])->execute();

            $role = Yii::$app->authManager->createRole($this->getSelfRoleName());
            Yii::$app->authManager->add($role);
            Yii::$app->authManager->assign($role, $this->id);
        }

        if ($this->_role !== null) {
            $authManager = Yii::$app->authManager;
            $authManager->revokeAll($this->id);
            foreach ((array)$this->_role as $roleName) {
                if (!$roleName) {continue;}

                $role = $authManager->getRole($roleName);
                if ($role === null) {
                    $role = $authManager->createRole($roleName);
                    $authManager->add($role);
                }

                $authManager->assign($role, $this->id);
            }
        }

        ($cache = Yii::$app->cache) && TagDependency::invalidate($cache, 'AdminUser'.$this->id);
        parent::afterSave($insert, $changedAttributes);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    protected $_password;
    public function setPassword($password)
    {
        $this->_password = $password;
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }
    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    protected static $_roleMaps;
    protected static function roleMaps($userId)
    {
        if (empty($userId)) {
            return [];
        }
        if (null === static::$_roleMaps) {
            static::$_roleMaps = [];
            /** @var DbManager $authManager */
            $authManager = Yii::$app->authManager;
            $rows = (new Query())->select('name,user_id')
                ->from("$authManager->assignmentTable a")
                ->innerJoin("$authManager->itemTable b", 'a.item_name=b.name')
                ->where(['type' => Item::TYPE_ROLE])
                ->all();
            static::$_roleMaps = ArrayHelper::map($rows, 'name', 'name', 'user_id');
        }

        return ArrayHelper::getValue(static::$_roleMaps, $userId, []);
    }

    public function getRole()
    {
        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        return array_filter(ArrayHelper::getColumn($roles, 'name'));
    }

    protected $_role;
    public function setRole($role)
    {
        $this->_role = $role;
    }

    public function getRoleDesc()
    {
        return implode('，', $this->getRole());
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getIsActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function getIsAvailable()
    {
        return $this->getIsActive() && $this->unlock_time < time();
    }

    public static function map()
    {
        $rows = static::find()->select('id, realname')->all();
        return ArrayHelper::map($rows, 'id', 'realname');
    }

    public function beforeValidate()
    {
        if (!$this->auth_key) {
            $this->generateAuthKey();
        }
        return parent::beforeValidate();
    }

    public function unlock()
    {
        $this->unlock_time = 0;
        $this->save(false);
    }

    public function getLast_login_time_txt()
    {
        return date('Y-m-d H:i:s', $this->last_login_time);
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function getIsMeizuUser()
    {
        return substr($this->email, -10) === '@meizu.com';
    }

    public function sendResetPasswordEmail()
    {
        $this->generatePasswordResetToken();
        if (!$this->save()) {
            return false;
        }

        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $this]
            )
            ->setFrom(['admin@meizu.com']) //@todo
            ->setTo($this->email)
            ->setSubject('密码重置 ' . Yii::$app->name)
            ->send();
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = ArrayHelper::getValue(Yii::$app->params, 'mz.admin.user.passwordResetTokenExpire', 3600);
        return $timestamp + $expire >= time();
    }

    public static function isPasswordMatchRule($password, &$err)
    {
        // 密码大于8位,大写小写数字符号四个包含三个
        $msg = '密码长度至少8位且包含大写小写数字符号中的3种';
        if (strlen($password) < 8) {
            $err = $msg;
            return false;
        }
        $p1 = preg_match('/[a-z]+/', $password);
        $p2 = preg_match('/[A-Z]+/', $password);
        $p3 = preg_match('/\d+/', $password);
        $p4 = preg_match('/[^a-zA-Z\d]+/', $password);
        if ($p1 + $p2 + $p3 + $p4 < 3) {
            $err = $msg;
            return false;
        }
        return true;
    }

    public function getParent()
    {
        return $this->hasOne(static::className(), ['id' => 'uid'])
            ->viaTable('{{%user_child}}', ['child_uid' => 'id']);
    }

    public static function import($dataRows, $extraAttrs = [])
    {
        $successCount = 0;
        $failCount = 0;
        $errors = [];
        $password = Yii::$app->security->generateRandomString(6); //随机生成密码
        foreach ($dataRows as $user) {
            $model = new AdminUser($user);
            if (!empty($user['role'])) {
                $model->role = $user['role'];
            }
            foreach ($extraAttrs as $k => $v) {
                $model->$k = $v;
            }
            try {
                $model->password = $password;
                if ($model->save()) {
                    $successCount++;
                } else {
                    $failCount++;
                    $errors[$model->email] = $model->errors;
                }
            } catch (Exception $e) {
                $failCount++;
                $errors[$model->email] = $e->getMessage();
            }
        }

        return compact('successCount', 'failCount', 'errors');
    }
}
