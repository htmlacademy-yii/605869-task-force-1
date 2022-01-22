<?php

    namespace frontend\controllers;

    use frontend\behavior\UserLastActivityBehavior;
    use yii\filters\AccessControl;
    use yii\filters\VerbFilter;
    use yii\web\Controller;

    abstract class SecuredController extends Controller
    {
        public function behaviors()
        {
            return [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@']
                        ],
                        [
                            'allow' => false,
                            'roles' => ['?'],
                            'denyCallback' => function ($rule, $action) {
                                return $this->goHome();
                            }
                        ]
                    ]
                ],
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'response' => ['POST'],
                    ],
                ],
                UserLastActivityBehavior::class,
            ];
        }
    }
