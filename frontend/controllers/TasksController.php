<?php

namespace frontend\controllers;

use TaskForce\Task;

/**
 * Class tasksController
 * @package frontend\controllers
 */
class TasksController extends \yii\web\Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        $tasks = \frontend\models\Task::find()->where(['status_id' => 1])->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}
