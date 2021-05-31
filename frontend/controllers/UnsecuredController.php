<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;

abstract class UnsecuredController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'allow' => false,
                        'roles' => ['@'],
                        'denyCallback' => function ($rule, $action) {
                            return $this->redirect('/tasks');
                        }
                    ]
                ]
            ]
        ];
    }
}