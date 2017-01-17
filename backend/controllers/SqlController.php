<?php

namespace backend\controllers;

use backend\models\SqlQueryForm;

class SqlController extends BaseController
{
    public function actionQuery()
    {
        $model = new SqlQueryForm();
        $model->load(\Yii::$app->request->get());
        $dataProvider = $model->query();
        return $this->render('query', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

}
