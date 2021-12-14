<?php

    $params = array_merge(
        require __DIR__ . '/../../common/config/params.php',
        require __DIR__ . '/../../common/config/params-local.php',
        require __DIR__ . '/params.php',
        require __DIR__ . '/params-local.php'
    );

    return [
        'id' => 'app-frontend',
        'basePath' => dirname(__DIR__),
        'bootstrap' => ['log'],
        'controllerNamespace' => 'frontend\controllers',
        'language' => 'ru-RU',
        'defaultRoute' => 'landing',
        'components' => [
            'request' => [
                'csrfParam' => '_csrf-frontend',
            ],
            'user' => [
                'identityClass' => 'frontend\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
            ],
            'session' => [
                // this is the name of the session cookie used for login on the frontend
                'name' => 'advanced-frontend',
            ],
            'log' => [
                'traceLevel' => YII_DEBUG ? 3 : 0,
                'targets' => [
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['error', 'warning'],
                    ],
                ],
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'authClientCollection' => [
                'class' => 'yii\authclient\Collection',
                'clients' => [
                    'vkontakte' => [
                        'class' => 'yii\authclient\clients\VKontakte',
                        'clientId' => '8017182',
                        'clientSecret' => 'hfE3zRa8vWgslYcG7Dj8',
                        'scope' => 'email',
                        'apiVersion' => '5.131'
                    ],
                ],
            ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'rules' => [
                    '/' => 'landing/index',
                    '/tasks' => 'tasks/index',
                    '/task/<id:\d+>' => 'tasks/view',
                    '/user/<id:\d+>' => 'users/view',
                    'GET /api/messages' => 'api/messages/get',
                    'POST /api/messages' => 'api/messages/add',
                ],
            ],
        ],
        'modules' => [
            'api' => [
                'class' => 'frontend\modules\api\ApiModule',
            ],
        ],
        'params' => $params,
    ];
