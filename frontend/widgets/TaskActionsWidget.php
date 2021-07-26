<?php

namespace frontend\widgets;

use frontend\models\CompleteTaskForm;
use frontend\models\ResponseTaskForm;
use frontend\models\Task;
use frontend\models\User;
use TaskForce\TaskActionStrategy;
use yii\base\Widget;
use Yii;

class TaskActionsWidget extends Widget
{
    /** @var Task */
    public Task $task;

    /** @inheritdoc */
    public function run()
    {
        $strategy = new TaskActionStrategy($this->task, User::findOne(Yii::$app->user->identity->getId()));

        $responseTaskForm = new ResponseTaskForm($this->task);
        $completeTaskForm = new CompleteTaskForm();

        return $this->render(
            'actions-widget',
            [
                'strategy' => $strategy,
                'task' => $this->task,
                'responseTaskForm' => $responseTaskForm,
                'completeTaskForm' => $completeTaskForm,
            ]
        );
    }
}
