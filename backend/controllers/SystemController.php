<?php
namespace backend\controllers;
use backend\Helper;
use backend\models\KeyStorage;
use backend\PopupTrait;

/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/30/16
 * Time: 1:31 PM
 */

class SystemController extends BaseController
{
    use PopupTrait;
    public function actionIndex()
    {
        $model = new KeyStorage();
        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            $model->save();
            Helper::successBack();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}