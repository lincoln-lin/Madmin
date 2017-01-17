<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace backend\controllers;

use backend\models\AuthItemProfile;
use backend\PopupTrait;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;
use backend\models\Route;
use backend\Helper;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RoleController extends ItemControllerAbstract
{
    use PopupTrait;

    /** @var string */
    protected $modelClass = 'backend\models\Role';

    protected $type = Item::TYPE_ROLE;

    /** @inheritdoc */
    protected function getItem($name)
    {
        $role = \Yii::$app->authManager->getRole($name);

        if ($role instanceof Role) {
            return $role;
        }

        throw new NotFoundHttpException;
    }

    public function actionDelete($name)
    {
        parent::actionDelete($name);
        AuthItemProfile::deleteAll(['item_name' => $name]);
        Helper::successBack('删除用户组成功');
    }

    public function actionPermission($name)
    {
        $item = $this->getItem($name);
        /** @var \backend\models\Role $model */
        $model = \Yii::createObject([
            'class' => $this->modelClass,
            'item' => $item,
        ]);

        if (\Yii::$app->request->isPost && $model->setPermissions(\Yii::$app->request->post('permissions'))) {
            $this->closeWindow('设置用户组权限成功');
        } else {
            $dataProvider = (new Route())->searchCheckable();
            return $this->render('permission', ['model' => $model, 'dataProvider' => $dataProvider]);
        }
    }

    /**
     * Shows update form.
     * @param  string $name
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($name)
    {
        /** @var \backend\models\Role|\backend\models\Permission $model */
        $item  = $this->getItem($name);
        $model = \Yii::createObject([
            'class'    => $this->modelClass,
            'scenario' => 'update',
            'item'     => $item,
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $this->closeWindow('设置用户组成功');
        } else {
            return $this->render('update', [
                'model' => $model,
                'dataProvider' => (new Route())->searchCheckable()
            ]);
        }
    }
}
