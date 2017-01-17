<?php

namespace backend\controllers;

use backend\Helper;
use backend\models\AdminMenu;
use backend\models\RouteForm;
use backend\PopupTrait;
use Yii;
use backend\models\Route;
use yii\data\ArrayDataProvider;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class RouteController extends BaseController
{
    use PopupTrait;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    /**
     * Lists all Route models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = (new Route())->search();
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 删除菜单
     */
    public function actionDelete($id)
    {
        if (!($menu = AdminMenu::findOne(['url' => $id]))) {
            throw new NotFoundHttpException('菜单不存在');
        }

        if ($menu->delete()) {
            Helper::success('删除菜单成功');
        } else {
            Helper::error($menu->_message_);
        }
        Helper::pageBack();
    }

    public function actionUpdate($id)
    {
        $route = (new Route())->findOne($id);
        if (!$route) {
            throw new NotFoundHttpException('路由不存在');
        }
        $model = new RouteForm(['route' => $route]);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->closeWindow();
        } else {
            return $this->render('update', ['model' => $model]);
        }
    }

}
