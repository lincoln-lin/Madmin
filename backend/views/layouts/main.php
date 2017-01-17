<?php
/**
 * Created by IntelliJ IDEA.
 * User: liujing <liujing@meizu.com>
 * Date: 5/16/16
 * Time: 5:23 PM
 */

use backend\Helper;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use backend\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

list($level1Items, $level2Items, $level3Items) = (new \backend\models\AdminMenuSearch())->getItemsOfLoggedUser();

$asset = \backend\assets\AdminAsset::register($this);

$this->registerJs($this->render('_main.js.php', [
    'popupCssUrl' => $asset->baseUrl.'/popup.css',
]), \yii\web\View::POS_HEAD);

$this->beginContent(__DIR__ . '/minimal.php');
?>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top mz-main-nav',
    ],
    'innerContainerOptions' => [
        'class' => 'container-fluid'
    ],
]);

$items = [
    Html::tag('li', Html::popup('个人信息', ['me/info'])),
    Html::tag('li', Html::popup('我的权限', ['me/permission'])),
];
if (Helper::checkRoute('/user-child/index')) {
    $items[] = Html::tag('li', Html::popup('我的子账号', ['user-child/index']));
}
if (Yii::$app->user->identity && !Yii::$app->user->identity->getIsMeizuUser()) {
    $items[] = Html::tag('li', Html::popup('修改密码', ['me/update-password']));
}
$items[] = '<li role="separator" class="divider"></li>';
$items[] = ['label' => '退出', 'url' => ['security/logout'], 'linkOptions' => [
    'data-method' => 'post',
]];


echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
//        ['label' => 'About', 'url' => ['/site/about']],
        Yii::$app->user->isGuest ? (
        ['label' => '登录', 'url' => Yii::$app->user->loginUrl]
        ) :
        [
            'label' => Yii::$app->user->identity->username,
            'items' => $items,
        ],
    ],
]);

echo Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $level1Items,
]);

NavBar::end();
?>

<div class="container-fluid mz-top-nav">
<?=
Nav::widget([
    'options' => ['class' => 'navbar-nav'],
    'items' => $level2Items,
])
?>
</div>


    <div class="container-fluid mz-page-container">
        <?=
        Nav::widget([
            'options' => ['class' => 'mz-left-nav'],
            'items' => $level3Items,
        ])
        ?>

        <div class="mz-main-content-box">
            <?= $this->render('../_alert.php') ?>

            <div class="mz-main-content-title">
                    <?= Html::encode($this->title) ?>
                <div class="pull-right mz-open-new-win"><?= \backend\helpers\Html::popup('新窗口中打开', null, ['class' => 'btn btn-default']) ?></div>
                <div class="pull-right mz-close-win"><button type="button" class="btn btn-danger" onclick="window.close();return false;">关闭窗口</button></div>

            </div>

            <?= $content ?>
        </div>


    </div>


<?php
$this->endContent();
