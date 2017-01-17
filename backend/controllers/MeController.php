<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 6/2/16
 * Time: 4:05 PM
 */

namespace backend\controllers;


use backend\models\AdminUserForm;
use backend\PopupTrait;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\Route;

class MeController extends BaseController
{
    use PopupTrait;
    
    public $needPermission = false;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionInfo()
    {
        $model = AdminUserForm::findOne(\Yii::$app->user->id);
        $model->scenario = 'user-info';
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            $this->closeWindow('保存成功');
        } else {
            return $this->render('info', [
                'model' => $model,
            ]);
        }
    }

    public function actionPermission()
    {
        $dataProvider = (new Route())->searchCheckable();
        return $this->render('permission', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdatePassword()
    {
        $model = AdminUserForm::findOne(\Yii::$app->user->id);
        $model->scenario = 'update-password';
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            \Yii::$app->user->logout();
            $this->closeWindow('修改密码成功');
        } else {
            return $this->render('update-password', [
                'model' => $model,
            ]);
        }
    }
}
