<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace backend\validators;

use yii\validators\Validator;
use yii\rbac\DbManager;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RbacValidator extends Validator
{
    /** @var DbManager */
    protected $manager;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        $this->manager = \Yii::$app->authManager;
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        if (!is_array($value)) {
            return ['错误的值', []];
        }

        foreach ($value as $val) {
            if ($this->manager->getRole($val) == null && $this->manager->getPermission($val) == null) {
                return ["{$val} 不是权限也不是用户组名称", []];
            }
        }
    }
}
