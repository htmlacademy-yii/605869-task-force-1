<?php

namespace frontend\controllers;

use TaskForce\Task;

/**
 * Class tasksController
 * @package frontend\controllers
 */
class tasksController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $tasks = \frontend\models\Task::find()->where(['status' => 'new'])->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}
