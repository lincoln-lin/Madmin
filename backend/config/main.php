<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => '某某管理系统',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'gii' => [
            'generators' => [
                'crud' => [
                    'class' => 'yii\gii\generators\crud\Generator',
                    'templates' => [
                        'meizu-backend' => '@backend/gii/crud/templates',
                    ],
                ],
            ],
        ],
    ],
    'components' => [
        'formatter' => [
            'nullDisplay' => '',
            'datetimeFormat' => 'php:Y-m-d H:i',
        ],
        'authManager' => [
            'class' => 'backend\components\DbManager',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'meizu' => [
                    'class' => 'backend\components\MeizuAuthClient',
                    'clientId' => 'iocL3sBdefsiokZUZKIW',
                    'clientSecret' => 'yKNvmGwhEAjOmaL6OTN7SDg2ak9KtD',
                ],
            ]
        ],
        'request' => [
            'class' => 'common\components\Request',
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'backend\models\AdminUser',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'loginUrl' => ['security/login'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'session-backend',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
