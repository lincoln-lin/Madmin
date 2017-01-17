<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 6/2/16
 * Time: 4:05 PM
 */

namespace backend\controllers;

use app\services\ContentPermissionService;
use backend\Helper;
use backend\models\AdminUserForm;
use backend\PopupTrait;
use yii\filters\AccessControl;
use yii\web\Controller;
use backend\models\Route;
use backend\models\AdminUserSearch;
use Yii;
use backend\models\AdminUser;
use yii\web\NotFoundHttpException;
use yii\db\Exception;
use app\models\CategoryPermission;
use app\models\CategorySearch;

class UserChildController extends BaseController
{
    use PopupTrait;

    public function actionIndex()
    {
        $searchModel = new AdminUserSearch();
        $searchModel->parentUid = Yii::$app->user->id;
        list($dataProvider, $roles) = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function checkRoleName($id)
    {
        if ($name !== '!!!'.$id && !$this->findModel($id)) {
            throw new NotFoundHttpException('参数错误');
        }
    }

    public function actionPermission($id)
    {
        $user = $this->findModel($id);
        $name = $user->getSelfRoleName();

        $item = Yii::$app->authManager->getRole($name);
        /** @var \backend\models\Role $model */
        $model = \Yii::createObject([
            'class' => '\backend\models\Role',
            'item' => $item,
        ]);

        if (\Yii::$app->request->isPost && $model->setPermissions(\Yii::$app->request->post('permissions'))) {
            $this->closeWindow('设置子账号权限成功');
        } else {
            $dataProvider = (new Route())->searchCheckable();
            return $this->render('permission', ['model' => $model, 'dataProvider' => $dataProvider]);
        }
    }

    public function actionContentPermission($id)
    {
        $user = $this->findModel($id);
        $name = $user->getSelfRoleName();

        if (\Yii::$app->request->isPost) {
            $cps = ContentPermissionService::getCategoryPermissionsByUser(Yii::$app->user->id);
            CategoryPermission::deleteAll(['item_name' => $name]);
            foreach (\Yii::$app->request->post('category') as $category_id => $item) {
                $categoryPermission = new CategoryPermission([
                    'category_id' => $category_id,
                    'item_name' => $name,
                ]);
                foreach (['is_read', 'is_write', 'is_manage'] as $prop) {
                    $categoryPermission->{$prop} = (
                        isset($item[$prop])
                        && $cps[$category_id][$prop]
                    ) ? 1 : 0;
                }
                $categoryPermission->save();
            }
            Helper::closeWindow('设置子账号的内容权限成功');
        }
        $permissions = CategoryPermission::find()->where(['item_name' => $name])->indexBy('category_id')->all();
        $dataProvider = (new CategorySearch())->search([]);
        return $this->render('../content-permission', [
            'permissions' => $permissions,
            'dataProvider' => $dataProvider,
            'role_name' => $name,
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

    public function actionCreate()
    {
        $model = new AdminUserForm();
        $model->parentUid = Yii::$app->user->id;

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
            if ($model->parent && $model->parent->id == Yii::$app->user->id) {
                return $model;
            }
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Helper::success('删除成功');
        } else {
            Helper::error($model->_message_ ?: '删除失败');
        }
        Helper::pageBack();
    }

    public function actionImport()
    {
        Yii::$app->response->format = 'json';

        return AdminUser::import(Yii::$app->request->post('users'), ['parentUid' => Yii::$app->user->id, 'role' => []]);
    }
}
