<?php
/**
 * Created by IntelliJ IDEA.
 * User: liu
 * Date: 5/30/16
 * Time: 2:16 PM
 */

use backend\components\ActiveForm;
use yii\bootstrap\Tabs;
use backend\helpers\Html;

/**
 * @var $this \yii\web\View
 * @var $model \backend\models\KeyStorage
 */

$this->title = '系统参数';

?>

<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'horizontalCssClasses' => [
            'label' => 'col-sm-2',
            'wrapper' => 'col-sm-5',
        ],
    ],
]) ?>

<?= Tabs::widget([
    'items' => [
        [
            'label' => '系统安全设置',
            'content' => $this->render('_security', ['model' => $model, 'form' => $form]),
        ],
        [
            'label' => '服务化安全设置',
            'content' => '<h1>Hello</h1>',
        ]
    ],
]) ?>
    <div class="action-area">
        <div class="form-group">     
            <div class="col-sm-2"></div>
            <div class="col-sm-6">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                <?= Html::resetButton('重置表单', ['class' => 'btn btn-default']) ?>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>