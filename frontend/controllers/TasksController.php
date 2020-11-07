<?php

namespace frontend\controllers;

use frontend\models\Task;

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
        $tasks = Task::find()->where(['status_id' => Task::STATUS_NEW])->all();

        return $this->render('index', [
            'tasks' => $tasks,
        ]);
    }

}
