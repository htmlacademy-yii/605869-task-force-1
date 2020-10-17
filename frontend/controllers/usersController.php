<?php

namespace frontend\controllers;

use frontend\models\Task;
use frontend\models\User;

/**
 * Class usersController
 * @package frontend\controllers
 */
class usersController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $users = User::find()->where(['role' => '1'])->all();
        return $this->render('index', [
            'users' => $users,
        ]);
    }

}
