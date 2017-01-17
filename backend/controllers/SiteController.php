<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 8/4/16
 * Time: 09:56
 */

namespace backend\controllers;


use yii\web\Controller;
use yii\filters\AccessControl;

class SiteController extends BaseController
{
    public $needLogin = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }
}
