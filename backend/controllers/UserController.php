<?php

namespace backend\controllers;

use backend\Helper;
use backend\models\AuthItemProfile;
use backend\PopupTrait;
use Yii;
use backend\models\AdminUser;
use backend\models\AdminUserSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;
use backend\models\AdminUserForm;

/**
 * UserController implements the CRUD actions for AdminUser model.
 */
class UserController extends BaseController
{
    use PopupTrait;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all AdminUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = 'raw';
            return AdminUser::updateAll(['status' => AdminUser::STATUS_FORBIDDEN], ['id' => Yii::$app->request->post('keys')]);
        }

        $searchModel = new AdminUserSearch();
        list($dataProvider, $roles) = $searchModel->search(Yii::$app->request->queryParams);

        $aps = AuthItemProfile::find()->where(['item_name' => ArrayHelper::getColumn($roles, 'item_name')])->indexBy('item_name')->all();

        $rolesMap = ArrayHelper::map($roles, 'item_name', 'item_name', 'user_id');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rolesMap' => $rolesMap,
            'aps' => $aps,
        ]);
    }

    /**
     * Creates a new AdminUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminUserForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->closeWindow('添加用户成功');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AdminUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post('unlock')) {
            $model->unlock();
            return;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->closeWindow('编辑用户成功');
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Finds the AdminUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminUserForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }



    public function actionImport()
    {
        Yii::$app->response->format = 'json';

        return AdminUser::import(Yii::$app->request->post('users'));
    }
}
