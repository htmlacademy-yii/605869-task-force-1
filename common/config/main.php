<?php
return [

    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => true,
////            'enableStrictParsing' => false,
//            'rules' => [
//                // ...
//            ],
//        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
