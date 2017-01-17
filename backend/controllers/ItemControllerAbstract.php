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

use backend\models\Role;
use backend\PopupTrait;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use backend\models\AuthItemSearch;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
abstract class ItemControllerAbstract extends BaseController
{
    use PopupTrait;
    /**
     * @param  string $name
     * @return \backend\models\Role|\backend\models\Permission
     */
    abstract protected function getItem($name);

    /**
     * @var int
     */
    protected $type;

    /**
     * @var string
     */
    protected $modelClass;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('Model class should be set');
        }
        if ($this->type === null) {
            throw new InvalidConfigException('Auth item type should be set');
        }
    }

    /**
     * Lists all created items.
     * @return string
     */
    public function actionIndex()
    {
        $filterModel = new AuthItemSearch($this->type);

        list($dataProvider, $profiles, $childs) = $filterModel->search(\Yii::$app->request->get());

        return $this->render('index', [
            'filterModel'  => $filterModel,
            'dataProvider' => $dataProvider,
            'profiles' => $profiles,
            'childs' => $childs,
        ]);
    }

    /**
     * Shows create form.
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        /** @var \backend\models\Role|\backend\models\Permission $model */
        $model = \Yii::createObject([
            'class'    => $this->modelClass,
            'scenario' => 'create',
        ]);

        $this->performAjaxValidation($model);

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            if ($model instanceof Role) {
                $this->closeWindow('创建用户组成功');
            } else {
                $this->closeWindow('创建权限成功');
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
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
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes item.
     * @param  string $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($name)
    {
        $item = $this->getItem($name);
        \Yii::$app->authManager->remove($item);
    }
    //*/

    /**
     * Performs ajax validation.
     * @param Model $model
     * @throws \yii\base\ExitException
     */
    protected function performAjaxValidation(Model $model)
    {
        if (\Yii::$app->request->isAjax && $model->load(\Yii::$app->request->post())) {
            \Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($model));
            \Yii::$app->end();
        }
    }
}
